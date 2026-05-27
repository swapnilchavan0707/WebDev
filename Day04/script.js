// Central Data Silo containing verified high-res live display graphic links from Unsplash
const blogDatabase = {
    "1": {
        title: "My First Blog",
        date: "Oct 4, 2025",
        category: "System Structures",
        image: "images/blog1.jpg",
        content: "<p>Welcome to my premium front-end web presentation dashboard. Crafting modern responsive products demands a keen eye for aesthetic color placement, structured modular file management, and unified element padding layout grids.</p><p>Throughout this internship workspace environment, we analyze and rebuild raw layout foundations into fluid responsive applications. This ensures absolute readability on high-end desktop configurations as well as mobile portrait layouts.</p><p>By prioritizing web standards and modular code, this platform forms the direct launchpad for future full-stack architecture modules.</p>"
    },
    "2": {
        title: "Mastering CSS Flexbox",
        date: "Oct 12, 2025",
        category: "Layout Models",
        image: "images/blog2.jpg",
        content: "<p>The Flexbox Layout Engine completely resolved traditional block alignment vulnerabilities. Rather than wrestling with manual float calculations or absolute spatial positions, setting an object to display flex grants fluid control over spatial distribution pipelines.</p><p>Using rules like justify-content space-between and align-items center, columns wrap symmetrically and gracefully adapt to layout adjustments instantly, keeping typography blocks clean and centered.</p><p>Understanding alignment weights allows engineers to configure clean multi-layered application sheets without cluttering script memory files.</p>"
    },
    "3": {
        title: "The Power of JavaScript",
        date: "Oct 20, 2025",
        category: "Runtime Logic",
        image: "images/blog3.jpg",
        content: "<p>JavaScript provides the functional engine that powers interactive web pages. While basic HTML maps layout structures and raw CSS applies attractive visual coats, scripts handle state tracking and real-time element transformations.</p><p>The filter script linked to our home canopy parses character string indices against real-time card models via native arrays, changing visibility attributes immediately without requiring server reloads.</p><p>Harnessing events enables frontend nodes to operate natively as responsive systems, vastly enhancing immediate visitor satisfaction ratings across all devices.</p>"
    }
};

// Automatically compile code loops into designated template containers on load
document.addEventListener("DOMContentLoaded", () => {
    generateGridCards('blogGrid');
    generateGridCards('archiveGrid');
});

// Structural HTML construction generator function logic
function generateGridCards(targetContainerId) {
    const targetElement = document.getElementById(targetContainerId);
    if (!targetElement) return;
    
    targetElement.innerHTML = ''; // Clear container buffers
    
    Object.keys(blogDatabase).forEach(key => {
        const article = blogDatabase[key];
        const cardNode = document.createElement('div');
        cardNode.className = 'blog-card';
        cardNode.innerHTML = `
            <div class="card-img-wrapper">
                <img src="${article.image}" alt="${article.title}">
            </div>
            <div class="blog-card-content">
                <span class="category-tag">${article.category}</span>
                <h2>${article.title}</h2>
                <p class="date-text">Published on: ${article.date}</p>
                <p class="excerpt-text">Explore detailed technical analysis on foundational components inside our complete article view layout...</p>
                <a href="#" class="read-more-btn" onclick="openFullArticle('${key}', event)">Read Full Article &rarr;</a>
            </div>
        `;
        targetElement.appendChild(cardNode);
    });
}

// Single-Page Framework Tab Switching Core Engine Logic
function switchTab(targetTabId) {
    // Hide all application view sections
    document.querySelectorAll('.app-section').forEach(section => {
        section.classList.remove('active-section');
    });
    
    // Deactivate highlight tokens across navigation lines
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Activate target block elements
    const selectedSection = document.getElementById(`tab-${targetTabId}`);
    if (selectedSection) {
        selectedSection.classList.add('active-section');
    }
    
    const activeNavLink = document.getElementById(`nav-${targetTabId}`);
    if (activeNavLink) {
        activeNavLink.classList.add('active');
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Full narrative injection script tracking mechanism
function openFullArticle(postId, event) {
    if (event) event.preventDefault();
    
    const targetStory = blogDatabase[postId];
    if (!targetStory) return;
    
    // Inject dataset elements directly into placeholder containers
    document.getElementById('articleCategory').textContent = targetStory.category;
    document.getElementById('articleTitle').textContent = targetStory.title;
    document.getElementById('articleDate').textContent = "Published on: " + targetStory.date;
    document.getElementById('articleImg').src = targetStory.image;
    document.getElementById('articleBody').innerHTML = targetStory.content;
    
    // Mount the template directly via SPA route calls
    switchTab('detail');
}

// Form validation alert callbacks
function handleContactSubmit(event) {
    event.preventDefault();
    alert("Thank you! Your message has been transmitted successfully to our editorial desk.");
    event.target.reset();
    switchTab('home');
}

// Real-time live client search box filtration query string index matches
function searchPosts() {
    const query = document.getElementById("searchBox").value.toLowerCase();
    const activeCards = document.querySelectorAll("#blogGrid .blog-card");
    
    activeCards.forEach(card => {
        const titleText = card.querySelector("h2").textContent.toLowerCase();
        card.style.display = titleText.includes(query) ? "" : "none";
    });
}
