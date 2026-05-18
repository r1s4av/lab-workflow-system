const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();
const PORT = 3000;

app.use(cors());
app.use(bodyParser.json());

app.use(express.static(__dirname));

app.use('/technician', express.static(path.join(__dirname, 'technician')));
app.use('/doctor', express.static(path.join(__dirname, 'doctor')));
app.use('/receptionist', express.static(path.join(__dirname, 'receptionist')));
app.use('/assets', express.static(path.join(__dirname, 'assets')));

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'lab_workflow_db'
});

db.connect((err) => {
    if (err) {
        console.error('Database connection failed:', err);
        process.exit(1);
    }
    console.log('Connected to MySQL database');
    console.log('Server running at http://localhost:' + PORT);
});

app.post('/api/login', (req, res) => {
    const { username, password } = req.body;
    
    db.query('SELECT * FROM user WHERE username = ?', [username], (err, results) => {
        if (err) {
            res.json({ success: false, error: 'Database error' });
            return;
        }
        
        if (results.length === 0) {
            res.json({ success: false, error: 'User not found' });
            return;
        }
        
        const user = results[0];
        if (password === user.password) {
            res.json({ 
                success: true, 
                role: user.role, 
                user_id: user.user_id,
                username: user.username 
            });
        } else {
            res.json({ success: false, error: 'Invalid Password' });
        }
    });
});

app.post('/api/reset-password', (req, res) => {
    const { username, full_name, new_password } = req.body;
    
    db.query('SELECT * FROM user WHERE username = ? AND user_name = ?', [username, full_name], (err, results) => {
        if (err) {
            res.json({ success: false, error: 'Database error' });
            return;
        }
        
        if (results.length === 0) {
            res.json({ success: false, error: 'User not found. Please check username and full name.' });
            return;
        }
        
        db.query('UPDATE user SET password = ? WHERE username = ?', [new_password, username], (err) => {
            if (err) {
                res.json({ success: false, error: 'Failed to update password' });
                return;
            }
            res.json({ success: true, message: 'Password reset successfully! You can now login.' });
        });
    });
});

app.post('/api/register', (req, res) => {
    const { full_name, username, password, role } = req.body;
    
    db.query('SELECT * FROM user WHERE username = ?', [username], (err, results) => {
        if (err) {
            res.json({ success: false, error: 'Database error' });
            return;
        }
        
        if (results.length > 0) {
            res.json({ success: false, error: 'Username already exists. Please choose another username.' });
            return;
        }
        
        db.query('INSERT INTO user (user_name, username, password, role, created_at) VALUES (?, ?, ?, ?, NOW())', 
            [full_name, username, password, role], (err) => {
                if (err) {
                    res.json({ success: false, error: err.message });
                    return;
                }
                res.json({ success: true, message: 'Account created successfully! You can now login.' });
            });
    });
});

app.get('/api/dashboard', (req, res) => {
    db.query('SELECT COUNT(*) as total FROM patient', (err, patientsResult) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        
        db.query('SELECT COUNT(*) as total FROM sample', (err, samplesResult) => {
            if (err) {
                res.json({ success: false, error: err.message });
                return;
            }
            
            db.query('SELECT COUNT(*) as total FROM report', (err, reportsResult) => {
                if (err) {
                    res.json({ success: false, error: err.message });
                    return;
                }
                
                db.query("SELECT COUNT(*) as total FROM sample_test WHERE result_value IS NULL OR result_value = ''", (err, pendingResult) => {
                    if (err) {
                        res.json({ success: false, error: err.message });
                        return;
                    }
                    
                    db.query("SELECT COUNT(*) as total FROM sample_test WHERE result_value IS NOT NULL AND result_value != ''", (err, completedResult) => {
                        if (err) {
                            res.json({ success: false, error: err.message });
                            return;
                        }
                        
                        db.query(`
                            SELECT p.patient_name, s.sample_id, st.performed_at
                            FROM sample_test st
                            JOIN sample s ON st.sample_id = s.sample_id
                            JOIN patient p ON s.patient_id = p.patient_id
                            ORDER BY st.performed_at DESC LIMIT 5
                        `, (err, recent) => {
                            if (err) {
                                res.json({ success: false, error: err.message });
                                return;
                            }
                            
                            res.json({ 
                                success: true,
                                total_patients: patientsResult[0].total,
                                total_samples: samplesResult[0].total,
                                total_reports: reportsResult[0].total,
                                pending_tests: pendingResult[0].total,
                                completed_tests: completedResult[0].total,
                                recent_activity: recent || []
                            });
                        });
                    });
                });
            });
        });
    });
});

app.get('/api/patients', (req, res) => {
    db.query('SELECT patient_id, patient_name FROM patient ORDER BY patient_name ASC', (err, results) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        res.json({ success: true, patients: results });
    });
});

app.get('/api/tests-list', (req, res) => {
    db.query('SELECT test_id, test_name FROM test ORDER BY test_name ASC', (err, results) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        res.json({ success: true, tests: results });
    });
});

app.post('/api/add-patient', (req, res) => {
    const { patient_name, date_of_birth, gender, contact_number, email, address } = req.body;
    
    const query = `INSERT INTO patient (patient_name, date_of_birth, gender, contact_number, email, address, registration_date) 
                   VALUES (?, ?, ?, ?, ?, ?, NOW())`;
    
    db.query(query, [patient_name, date_of_birth, gender, contact_number, email, address], (err, result) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        res.json({ success: true, message: 'Patient registered successfully', patient_id: result.insertId });
    });
});

app.post('/api/add-sample', (req, res) => {
    const { patient_id, sample_type, test_ids } = req.body;
    
    db.query('INSERT INTO sample (sample_type, collection_date, sample_status, patient_id) VALUES (?, NOW(), "Pending", ?)', 
        [sample_type, patient_id], (err, result) => {
            if (err) {
                res.json({ success: false, error: err.message });
                return;
            }
            
            const sample_id = result.insertId;
            let completed = 0;
            
            if (!test_ids || test_ids.length === 0) {
                res.json({ success: true, message: 'Sample created successfully', sample_id: sample_id });
                return;
            }
            
            test_ids.forEach((test_id) => {
                db.query('INSERT INTO sample_test (sample_id, test_id) VALUES (?, ?)', 
                    [sample_id, test_id], (err) => {
                        if (err) console.log(err);
                        completed++;
                        if (completed === test_ids.length) {
                            res.json({ success: true, message: 'Sample created successfully', sample_id: sample_id });
                        }
                    });
            });
        });
});

app.get('/api/tests', (req, res) => {
    const query = `
        SELECT st.sample_test_id, p.patient_name, t.test_name,
               st.result_value, st.performed_at
        FROM sample_test st
        JOIN sample s ON st.sample_id = s.sample_id
        JOIN patient p ON s.patient_id = p.patient_id
        JOIN test t ON st.test_id = t.test_id
        ORDER BY st.performed_at DESC
    `;
    
    db.query(query, (err, results) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        res.json({ success: true, tests: results });
    });
});

app.post('/api/update-test', (req, res) => {
    const { id, result_value } = req.body;
    
    db.query('UPDATE sample_test SET result_value = ?, performed_at = NOW() WHERE sample_test_id = ?', 
        [result_value, id], (err) => {
            if (err) {
                res.json({ success: false, error: err.message });
                return;
            }
            
            db.query('SELECT report_id FROM report WHERE sample_test_id = ?', [id], (err, results) => {
                if (results.length === 0) {
                    db.query('INSERT INTO report (sample_test_id, generated_date, status, remarks, approved_by) VALUES (?, NOW(), "Pending", "", NULL)', 
                        [id], (err) => {
                            if (err) console.log(err);
                        });
                }
            });
            
            res.json({ success: true, message: 'Test result updated successfully' });
        });
});

app.get('/api/reports', (req, res) => {
    const query = `
        SELECT r.report_id, p.patient_name, t.test_name,
               st.result_value, r.generated_date, r.status
        FROM report r
        JOIN sample_test st ON r.sample_test_id = st.sample_test_id
        JOIN sample s ON st.sample_id = s.sample_id
        JOIN patient p ON s.patient_id = p.patient_id
        JOIN test t ON st.test_id = t.test_id
        ORDER BY r.generated_date DESC
    `;
    
    db.query(query, (err, results) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        res.json({ success: true, reports: results });
    });
});

app.post('/api/approve-report', (req, res) => {
    const { id } = req.body;
    
    db.query('UPDATE report SET status = "Approved" WHERE report_id = ?', [id], (err) => {
        if (err) {
            res.json({ success: false, error: err.message });
            return;
        }
        res.json({ success: true, message: 'Report approved successfully' });
    });
});

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'index.html'));
});

app.get('/login.html', (req, res) => {
    res.sendFile(path.join(__dirname, 'login.html'));
});

app.get('/dashboard.html', (req, res) => {
    res.sendFile(path.join(__dirname, 'dashboard.html'));
});

app.use((req, res) => {
    res.status(404).sendFile(path.join(__dirname, '404.html'));
});

app.listen(PORT, () => {
    console.log(`Server running at http://localhost:${PORT}`);
    console.log('Available pages:');
    console.log('  - http://localhost:3000/');
    console.log('  - http://localhost:3000/login.html');
    console.log('  - http://localhost:3000/dashboard.html');
    console.log('  - http://localhost:3000/technician/view_tests.html');
    console.log('  - http://localhost:3000/doctor/view_reports.html');
    console.log('  - http://localhost:3000/receptionist/add_patient.html');
});