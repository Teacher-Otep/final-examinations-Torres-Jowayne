function showSection(sectionID) {

    document.querySelectorAll('.content').forEach(section => {
        section.style.display = 'none';
    });

    document.querySelectorAll('.homecontent').forEach(section => {
        section.style.display = 'none';
    });

    const activeSection = document.getElementById(sectionID);
    if (activeSection) {
        activeSection.style.display = 'block';
    }
}

function clearFields() {
    const inputs = document.querySelectorAll('#create input[type="text"], #create input[type="number"]');
    inputs.forEach(input => input.value = '');
}


function showToast(toastId) {
    const toast = document.getElementById(toastId);
    if (!toast) return;
    toast.classList.remove('toast-hidden');
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.classList.add('toast-hidden');
            toast.style.opacity = '';
        }, 500);
    }, 3000);
}


window.onload = function () {
    const params = new URLSearchParams(window.location.search);
    const status = params.get('status');
    const section = params.get('section') || 'home';


    if (status === 'success') {
        showSection('create');
        showToast('success-toast');
    }

    else if (status === 'update_success') {
        showSection('update');
        showToast('update-success-toast');
    }
    else if (status === 'update_error') {
        showSection('update');
        showToast('update-error-toast');
    }

    else if (status === 'delete_success') {
        showSection('delete');
        showToast('delete-success-toast');
    }
    else if (status === 'delete_error') {
        showSection('delete');
        showToast('delete-error-toast');
    }

    else if (params.has('update_id')) {
        showSection('update');
    }

    else {
        showSection('home');
    }


    window.history.replaceState({}, document.title, window.location.pathname);
};
