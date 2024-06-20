document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.category').forEach(function(categoryLink) {

        categoryLink.addEventListener('click', function(event) {            
            event.preventDefault();
            const categoryId = event.target.getAttribute('data-id');

            fetch(`${siteData.siteUrl}/wp-json/layerup/v1/posts/${categoryId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('No response');
                    }
                    return response.json();
                })
                .then(data => {
                    const postList = document.getElementById('post-list');
                    postList.innerHTML = '';
                    if (data.length === 0) {
                        postList.innerHTML = '<li>No posts found.</li>';
                    } else {
                        data.forEach(post => {
                            const listItem = document.createElement('li');

                            const featuredDiv = document.createElement('div');
                            featuredDiv.className = 'featured';

                           if (post.thumbnail) {                               

                                const img = document.createElement('img');
                                img.src = post.thumbnail;  
                                featuredDiv.appendChild(img);                                
                            }

                            listItem.appendChild(featuredDiv);
                            
                            const title = document.createElement('h3');
                            title.textContent = post.title;
                            listItem.appendChild(title);
                            postList.appendChild(listItem);
                        });
                    }
                })
        });
    });
});