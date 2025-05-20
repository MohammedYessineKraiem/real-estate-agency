window.onload = function() {
    const welcomeMessage = document.getElementById('welcome-message');
    const currentHour = new Date().getHours();
  
    if (currentHour >= 0 && currentHour < 12) {
      welcomeMessage.innerText = "Good Morning, Welcome to the Admin Portal!";
    } else if (currentHour >= 12 && currentHour < 18) {
      welcomeMessage.innerText = "Good Afternoon, Welcome to the Admin Portal!";
    } else {
      welcomeMessage.innerText = "Good Evening, Welcome to the Admin Portal!";
    }
  
    // Call hover effect function on cards
    setupCardHoverEffects();
  };
  
  // Add hover effect to each card
  function setupCardHoverEffects() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
      card.addEventListener('mouseover', () => {
        card.style.transform = "scale(1.05)";
        card.style.boxShadow = "0 4px 15px rgba(0, 0, 0, 0.3)";
      });
  
      card.addEventListener('mouseout', () => {
        card.style.transform = "scale(1)";
        card.style.boxShadow = "none";
      });
    });
  }
  
  // Track the number of visible cards
  function trackVisibleCards() {
    const cards = document.querySelectorAll('.card');
    let visibleCards = 0;
  
    cards.forEach(card => {
      if (card.offsetParent !== null) { // if the card is visible
        visibleCards++;
      }
    });
  
    console.log(`Currently, there are ${visibleCards} visible cards.`);
  }
  
  // Setup smooth scrolling for anchor links
  document.querySelectorAll('a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href.startsWith('#')) {
        // Prevent default behavior only for in-page navigation
        e.preventDefault();
        const targetId = href.substring(1);
        const targetElement = document.getElementById(targetId);
  
        window.scrollTo({
          top: targetElement.offsetTop,
          behavior: 'smooth'
        });
      }
      // Else, allow default navigation behavior
    });
  });
  