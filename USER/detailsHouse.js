// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', function () {
    // Load existing comments from localStorage when the page loads
    loadComments();

    // Attach event listener to the 'Envoyer' button
    document.querySelector('.comment-form button').addEventListener('click', addComment);

    // onClick, onMouseOver, onMouseOut for the comment section
    const commentSection = document.querySelector('.comment-section');
    commentSection.onclick = function() {
        console.log('Comment section clicked');
    };
    commentSection.onmouseover = function() {
        console.log('Mouse over the comment section');
    };
    commentSection.onmouseout = function() {
        console.log('Mouse out of the comment section');
    };

    // onLoad for the image
    const img = document.getElementById('img');
    img.onload = function() {
        console.log('Image loaded successfully');
    };

    // onBlur, onFocus for comment input
    const commentInput = document.querySelector('.comment-form textarea');
    commentInput.onblur = function() {
        console.log('Comment input lost focus');
    };
    commentInput.onfocus = function() {
        console.log('Comment input focused');
    };

    // onChange for the rating input
    const ratingInput = document.querySelector('.comment-form input');
    ratingInput.onchange = function() {
        console.log('Rating changed to ' + ratingInput.value);
    };

    // onSubmit, onReset
    const form = document.querySelector('.comment-form');
    form.onsubmit = function(e) {
        e.preventDefault();
        console.log('Form submitted');
    };
    form.onreset = function() {
        console.log('Form reset');
    };
});

// Function to load comments from localStorage
function loadComments() {
    const storedComments = JSON.parse(localStorage.getItem('comments')) || [];
    const commentSection = document.querySelector('.comment-section');
    
    // Loop through the stored comments and add them to the page
    storedComments.forEach(comment => {
        const newComment = document.createElement('section');
        newComment.classList.add('comment');
        newComment.innerHTML = `
            <img src="assets/3135715.png" alt="Profil">
            <section class="comment-content">
                <p><strong>${comment.name}</strong> - <span class="stars">${'★'.repeat(comment.rating)}</span></p>
                <p>${comment.text}</p>
                <p><small>Posté le ${comment.date}</small></p>
            </section>
        `;
        commentSection.appendChild(newComment);
    });
}

// Function to add a new comment and save it to localStorage
function addComment() {
    const commentText = document.querySelector('.comment-form textarea').value;
    const rating = document.querySelector('.comment-form input').value;
    
    // Check if comment and rating are provided
    if (commentText && rating) {
        const commentSection = document.querySelector('.comment-section');

        // Get current date
        const currentDate = new Date().toLocaleDateString();

        // Create the comment object
        const newComment = {
            name: "Votre Nom",
            text: commentText,
            rating: parseInt(rating),
            date: currentDate
        };

        // Save the comment to localStorage
        const storedComments = JSON.parse(localStorage.getItem('comments')) || [];
        storedComments.push(newComment);
        localStorage.setItem('comments', JSON.stringify(storedComments));

        // Add the new comment to the page
        const newCommentElement = document.createElement('section');
        newCommentElement.classList.add('comment');
        newCommentElement.innerHTML = `
            <img src="assets/3135715.png" alt="Profil">
            <section class="comment-content">
                <p><strong>${newComment.name}</strong> - <span class="stars">${'★'.repeat(newComment.rating)}</span></p>
                <p>${newComment.text}</p>
                <p><small>Posté le ${newComment.date}</small></p>
            </section>
        `;
        commentSection.appendChild(newCommentElement);

        // Clear the form fields after adding the comment
        document.querySelector('.comment-form textarea').value = '';
        document.querySelector('.comment-form input').value = '';
    } else {
        alert('Veuillez remplir le commentaire et la note.');
    }
}
