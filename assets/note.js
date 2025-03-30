document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('saveNote').addEventListener('click', async function () {
        const response = await fetch('/api/notes', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title: document.getElementById('noteTitle').value,
                content: document.getElementById('noteContent').value,
                category: document.getElementById('noteCategory').value,
                project_id: 1 // Set your project ID here
            })
        });

        if (response.ok) {
            window.location.reload();
        }
    });

    // Add feedback
    document.querySelectorAll('.add-feedback').forEach(button => {
        button.addEventListener('click', async function () {
            const noteId = this.dataset.noteId;
            const feedback = this.previousElementSibling.value;

            const response = await fetch(`/api/notes/${noteId}/feedback`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ feedback })
            });

            if (response.ok) {
                const data = await response.json();
                this.closest('.note-card').querySelector('.note-content').innerHTML = data.content;
                this.previousElementSibling.value = '';
            }
        });
    });
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            if (confirm('Weet je zeker dat je deze note wil verwijderen?')) {
                const form = this.closest('form');
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value
                        },
                        body: new FormData(form)
                    });

                    if (response.ok) {
                        form.closest('.note-card').remove();
                    }
                } catch (error) {
                    console.error('Verwijderen mislukt: ', error);
                }
            }
        });
    });
});