document.addEventListener('DOMContentLoaded', function() {
    // Fetch and display posts when the page loads
    fetchPosts();

    // Handle form submission
    const form = document.getElementById('postForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const username = document.getElementById('username').value;
        const content = document.getElementById('content').value;
        const avatarFile = document.getElementById('avatar').files[0]; // Get avatar file

        if (username && content && avatarFile) {
            const formData = new FormData();
            formData.append('username', username);
            formData.append('content', content);
            formData.append('avatar', avatarFile); // Append the file to FormData

            // Post the data using the Fetch API
            fetch('posts.php', {
                method: 'POST',
                body: formData // Send FormData directly
            })
            .then(response => response.json()) // Expecting a JSON response
            .then(data => {
                console.log(data.message); // Log success message
                // Clear the form after submission
                form.reset();
                // Fetch and refresh posts
                fetchPosts();
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // Fetch posts and display them
    function fetchPosts() {
        fetch('posts.php')
            .then(response => response.json())
            .then(data => {
                const postsDiv = document.getElementById('posts');
                postsDiv.innerHTML = ''; // Clear existing posts

                data.forEach(post => {
                    const postElement = `
                        <div class="post">
                            <img src="data:image/jpeg;base64,${post.avatar}" class="avatar" alt="Avatar"> <!-- Assuming you convert BLOB to base64 -->
                            <div>
                                <span class="username">${post.username}</span>
                                <span class="time">${post.time}</span>
                                <div class="content">${post.content}</div>
                            </div>
                        </div>
                    `;
                    postsDiv.innerHTML += postElement;
                });
            })
            .catch(error => console.error('Error:', error));
    }
});