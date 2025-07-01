import "./bootstrap";
import Swal from "sweetalert2";

// Make SweetAlert2 available globally
window.Swal = Swal;

// Global SweetAlert functions
window.showSuccessAlert = function (message) {
    Swal.fire({
        icon: "success",
        title: "Success!",
        text: message,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
};

window.showErrorAlert = function (message) {
    Swal.fire({
        icon: "error",
        title: "Error!",
        text: message,
        confirmButtonText: "OK",
    });
};

window.showWarningAlert = function (message) {
    Swal.fire({
        icon: "warning",
        title: "Warning!",
        text: message,
        confirmButtonText: "OK",
    });
};
