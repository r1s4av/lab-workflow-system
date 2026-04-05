</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function handleFormSubmit(formId, buttonId) {
    const form = document.getElementById(formId);
    const btn = document.getElementById(buttonId);

    form.addEventListener("submit", function () {
        btn.disabled = true;
        btn.innerHTML = "Saving... ⏳";

        // Fail-safe timeout (10 sec)
        setTimeout(() => {
            if (btn.disabled) {
                alert("⚠️ Something went wrong or server is slow.\nPlease try again.");
                btn.disabled = false;
                btn.innerHTML = "Retry";
            }
        }, 10000);
    });
}
</script>

<div id="toast" class="toast-msg"></div>

<script>
function showToast(msg){
    let t = document.getElementById("toast");
    t.innerText = msg;
    t.classList.add("show");

    setTimeout(()=>{
        t.classList.remove("show");
    },3000);
}
</script>

</body>
</html>