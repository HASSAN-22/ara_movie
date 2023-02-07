const Toast = {
    show(message,bg="#e80056",color="#eee",time=3000){
        var x = document.getElementById("snackbar");
        x.className = "show";
        x.innerText = message;
        x.style.cssText += `color:${color};background-color:${bg}`;
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, time);
    },
}
export default Toast