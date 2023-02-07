import swal from 'sweetalert2';
window.Swal = swal;
const Alert = {
    show(title='موفق',text='با موفقیت ثبت شد',icon='success',timer=4000000){
        Swal.fire({
            title: title,
            html: text,
            icon: icon,
            showCancelButton: false,
            showDenyButton: true,
            denyButtonText: "بستن",
            timer:timer,
        });
    }
}

export default Alert