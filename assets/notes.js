const addNoteBtn = document.getElementById('addNoteBtn');
const newNote = document.getElementById('newNote');
const notesContainer = document.getElementById('notesContainer');
const themeToggle = document.getElementById('themeToggle');

let editingNoteIndex = null;

window.addEventListener('load', () => {
    const savedNotes = JSON.parse(localStorage.getItem('notes')) || [];
    savedNotes.forEach((note, index) => createNoteElement(note, index));
});

addNoteBtn.addEventListener('click', () => {
    const noteText = newNote.value;
    if (noteText.trim() === '') return;

    if (editingNoteIndex !== null) {
        updateNoteInLocalStorage(noteText, editingNoteIndex);
        resetInput();
    } else {
        createNoteElement(noteText);
        saveNoteToLocalStorage(noteText);
    }
    newNote.value = '';
});

function createNoteElement(text, index = null) {
    const noteDiv = document.createElement('div');
    noteDiv.classList.add('note');

    const noteText = document.createElement('pre');
    noteText.textContent = text;

    const editBtn = document.createElement('button');
    editBtn.textContent = 'Edit';
    editBtn.addEventListener('click', () => editNote(text, index || getNoteIndex(text)));

    const deleteBtn = document.createElement('button');
    deleteBtn.textContent = 'Delete';
    deleteBtn.addEventListener('click', () => {
        noteDiv.remove();
        deleteNoteFromLocalStorage(text);
    });

    noteDiv.appendChild(noteText);
    noteDiv.appendChild(editBtn);
    noteDiv.appendChild(deleteBtn);
    notesContainer.appendChild(noteDiv);
}

function saveNoteToLocalStorage(note) {
    const notes = JSON.parse(localStorage.getItem('notes')) || [];
    notes.push(note);
    localStorage.setItem('notes', JSON.stringify(notes));
}

function updateNoteInLocalStorage(newText, index) {
    const notes = JSON.parse(localStorage.getItem('notes'));
    notes[index] = newText;
    localStorage.setItem('notes', JSON.stringify(notes));

    notesContainer.innerHTML = '';
    notes.forEach((note, i) => createNoteElement(note, i));
    editingNoteIndex = null;
}

function deleteNoteFromLocalStorage(note) {
    const notes = JSON.parse(localStorage.getItem('notes')) || [];
    const updatedNotes = notes.filter(n => n !== note);
    localStorage.setItem('notes', JSON.stringify(updatedNotes));
}

function editNote(text, index) {
    newNote.value = text;
    editingNoteIndex = index;
    addNoteBtn.textContent = 'Update Note';
}

function getNoteIndex(text) {
    const notes = JSON.parse(localStorage.getItem('notes'));
    return notes.indexOf(text);
}

function resetInput() {
    newNote.value = '';
    addNoteBtn.textContent = 'Add Note';
}

themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark');
    themeToggle.textContent = document.body.classList.contains('dark') ? '☀️' : '?';
});