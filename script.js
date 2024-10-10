document.addEventListener('DOMContentLoaded', function() {
    // Fetch and display posts when the page loads
    fetchPosts();

    // Handle form submission
    const form = document.getElementById('postForm');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const username = document.getElementById('username').value;
        const content = document.getElementById('content').value;

        if (username && content) {
            // Post the data using the Fetch API
            fetch('posts.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username: username, content: content })
            })
            .then(response => response.text())
            .then(data => {
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
                            <img src="avatar.png" class="avatar" alt="Avatar">
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