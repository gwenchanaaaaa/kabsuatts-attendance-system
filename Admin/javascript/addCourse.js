document.addEventListener('DOMContentLoaded', function() {
    // Get all forms
    const addCourseForm = document.getElementById('addCourseForm');
    const addUnitForm = document.getElementById('addUnitForm');
    const addFacultyForm = document.getElementById('addFacultyForm');
    const editCourseModal = document.getElementById('editCourseModal');
    const editUnitForm = document.getElementById('editUnitForm');
    const editDepartmentForm = document.getElementById('editDepartmentForm');
    const overlay = document.getElementById('overlay');

    console.log('Forms found:', {
        addCourseForm: !!addCourseForm,
        addUnitForm: !!addUnitForm,
        addFacultyForm: !!addFacultyForm,
        editCourseModal: !!editCourseModal,
        editUnitForm: !!editUnitForm,
        editDepartmentForm: !!editDepartmentForm,
        overlay: !!overlay
    });

    // Function to show a specific modal
    function showModal(form) {
        if (!form || !overlay) return;
        form.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    // Function to hide all modals
    function hideModals() {
        const forms = [addCourseForm, addUnitForm, addFacultyForm, editCourseModal, editUnitForm, editDepartmentForm];
        forms.forEach(form => {
            if (form) form.style.display = 'none';
        });
        if (overlay) overlay.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Handle clicks on the add buttons
    document.addEventListener('click', function(e) {
        const addButton = e.target.closest('.add');
        if (!addButton) return;

        e.preventDefault();
        const buttonText = addButton.textContent.trim().toLowerCase();
        console.log('Add button clicked:', buttonText);

        let formToShow = null;
        if (buttonText.includes('add course')) {
            formToShow = addCourseForm;
        } else if (buttonText.includes('add subject')) {
            formToShow = addUnitForm;
        } else if (buttonText.includes('add department')) {
            formToShow = addFacultyForm;
        }

        if (formToShow) showModal(formToShow);
    });

    // Handle clicks on edit buttons
    document.addEventListener('click', function(e) {
        const editButton = e.target.closest('.edit-button');
        if (!editButton) return;

        e.preventDefault();
        console.log('Edit button clicked');

        const type = editButton.dataset.type;
        console.log('Edit type:', type);

        let formToShow = null;
        switch (type) {
            case 'Course':
                formToShow = editCourseModal;
                if (formToShow) {
                    document.getElementById('edit_course_id').value = editButton.dataset.id;
                    document.getElementById('edit_course_name').value = editButton.dataset.name;
                    document.getElementById('edit_course_code').value = editButton.dataset.code;
                    const facultySelect = document.getElementById('edit_faculty');
                    if (facultySelect) {
                        facultySelect.value = editButton.dataset.faculty;
                    }
                }
                break;
            case 'Unit':
                formToShow = editUnitForm;
                if (formToShow) {
                    document.getElementById('edit-unitCode').value = editButton.dataset.id;
                    document.getElementById('edit-unitName').value = editButton.dataset.name;
                    document.getElementById('edit-courseName').value = editButton.dataset.course;
                }
                break;
            case 'Faculty':
                formToShow = editDepartmentForm;
                if (formToShow) {
                    document.getElementById('edit-facultyCode').value = editButton.dataset.id;
                    document.getElementById('edit-facultyName').value = editButton.dataset.name;
                }
                break;
        }

        if (formToShow) {
            console.log('Showing edit form:', formToShow.id);
            showModal(formToShow);
        }
    });

    // Handle clicks on close buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.close') || e.target.matches('.edit-close') || e.target.matches('.close-btn')) {
            hideModals();
        }
    });

    // Handle clicks on overlay
    if (overlay) {
        overlay.addEventListener('click', hideModals);
    }

    // Prevent modal close when clicking inside forms
    const allForms = [addCourseForm, addUnitForm, addFacultyForm, editCourseModal, editUnitForm, editDepartmentForm];
    allForms.forEach(form => {
        if (form) {
            form.addEventListener('click', e => e.stopPropagation());
        }
    });
});
