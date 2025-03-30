document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const notesGrid = document.getElementById('notesGrid');
    const noteEditor = document.getElementById('noteEditor');
    const noteTitle = document.getElementById('noteTitle');
    const noteContent = document.getElementById('noteContent');
    const saveNoteBtn = document.getElementById('saveNoteBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const newNoteBtn = document.getElementById('newNoteBtn');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    const pageInfo = document.getElementById('pageInfo');

    // State
    let currentNoteId = null;
    let currentPage = 1;
    let totalPages = 1;

    // Initialize
    loadNotes();

    // Event Listeners
    newNoteBtn.addEventListener('click', showNewNoteForm);
    saveNoteBtn.addEventListener('click', saveNote);
    cancelEditBtn.addEventListener('click', cancelEdit);
    prevPageBtn.addEventListener('click', goToPrevPage);
    nextPageBtn.addEventListener('click', goToNextPage);

    // Functions
    function showNewNoteForm() {
        currentNoteId = null;
        noteTitle.value = '';
        noteContent.value = '';
        noteEditor.classList.remove('hidden');
        noteTitle.focus();
    }

    function showEditNoteForm(note) {
        currentNoteId = note.id;
        noteTitle.value = note.title;
        noteContent.value = note.content;
        noteEditor.classList.remove('hidden');
        noteTitle.focus();
    }

    function cancelEdit() {
        noteEditor.classList.add('hidden');
    }

    async function loadNotes() {
        try {
            const response = await fetch(`/api/notes?page=${currentPage}`);
            const data = await response.json();

            notesGrid.innerHTML = '';

            data.data.forEach(note => {
                const noteElement = createNoteElement(note);
                notesGrid.appendChild(noteElement);
            });

            totalPages = data.meta.total_pages;
            updatePaginationControls();
        } catch (error) {
            console.error('Error loading notes:', error);
        }
    }

    function createNoteElement(note) {
        const noteElement = document.createElement('div');
        noteElement.className = 'note-card';

        noteElement.innerHTML = `
            <div class="note-card-header">
                <h3 class="note-card-title">${note.title}</h3>
                <div class="note-card-actions">
                    <button class="btn-icon edit-note" data-id="${note.id}">
                        <i class="icon-edit"></i>
                    </button>
                    <button class="btn-icon delete-note" data-id="${note.id}">
                        <i class="icon-delete"></i>
                    </button>
                </div>
            </div>
            <div class="note-card-content">${note.content}</div>
            <div class="note-card-footer">
                <span class="note-date">${note.created_at}</span>
            </div>
        `;

        // Add event listeners to the buttons we just created
        noteElement.querySelector('.edit-note').addEventListener('click', () => {
            showEditNoteForm(note);
        });

        noteElement.querySelector('.delete-note').addEventListener('click', () => {
            deleteNote(note.id);
        });

        return noteElement;
    }

    async function saveNote() {
        const title = noteTitle.value.trim();
        const content = noteContent.value.trim();

        if (!title || !content) {
            alert('Title and content are required');
            return;
        }

        const noteData = {
            title: title,
            content: content
        };

        try {
            let response;

            if (currentNoteId) {
                // Update existing note
                response = await fetch(`/api/notes/${currentNoteId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(noteData)
                });
            } else {
                // Create new note
                response = await fetch('/api/notes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(noteData)
                });
            }

            if (response.ok) {
                noteEditor.classList.add('hidden');
                loadNotes();
            } else {
                const error = await response.json();
                alert(error.error || 'Error saving note');
            }
        } catch (error) {
            console.error('Error saving note:', error);
            alert('Error saving note');
        }
    }

    async function deleteNote(id) {
        if (!confirm('Are you sure you want to delete this note?')) {
            return;
        }

        try {
            const response = await fetch(`/api/notes/${id}`, {
                method: 'DELETE'
            });

            if (response.ok) {
                loadNotes();
            } else {
                const error = await response.json();
                alert(error.error || 'Error deleting note');
            }
        } catch (error) {
            console.error('Error deleting note:', error);
            alert('Error deleting note');
        }
    }

    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            loadNotes();
        }
    }

    function goToNextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            loadNotes();
        }
    }

    function updatePaginationControls() {
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
    }
});