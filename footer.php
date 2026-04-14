</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ✅ LOADER FIX */
window.addEventListener("load", function(){
    const loader = document.getElementById("loader");
    if(loader){
        loader.style.display = "none";
    }
});

/* FAIL SAFE */
setTimeout(() => {
    const loader = document.getElementById("loader");
    if(loader){
        loader.style.display = "none";
    }
}, 3000);
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