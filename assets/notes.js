document.addEventListener("DOMContentLoaded", function () {
    const addNoteBtn = document.getElementById("addNoteBtn");
    const newNoteInput = document.getElementById("newNote");
    const notesContainer = document.getElementById("notesContainer");

    fetch("/notes")
        .then(response => response.json())
        .then(data => {
            data.forEach(note => addNoteToUI(note.id, note.content));
        });

    addNoteBtn.addEventListener("click", function () {
        const content = newNoteInput.value.trim();
        if (!content) {
            alert("Typ een notitie!");
            return;
        }

        fetch("/notes/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ content: content })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("Fout bij opslaan: " + data.error);
                } else {
                    addNoteToUI(data.id, content);
                    newNoteInput.value = "";
                }
            })
            .catch(error => console.error("Error:", error));
    });

    function addNoteToUI(id, content) {
        const noteElement = document.createElement("div");
        noteElement.classList.add("note");
        noteElement.dataset.id = id;
        noteElement.innerHTML = `
            <pre>${content}</pre>
            <button onclick="deleteNote(${id}, this)">🗑</button>
        `;
        notesContainer.prepend(noteElement);
    }
});

function deleteNote(id, buttonElement) {
    fetch(`/notes/delete/${id}`, {
        method: "DELETE",
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                buttonElement.parentElement.remove();
            } else {
                alert("Fout bij verwijderen!");
            }
        })
        .catch(error => console.error("Error:", error));
}
