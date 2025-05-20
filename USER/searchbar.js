// Sample property data (replace with your actual data)
const properties = [
  {
    id: 1,
    title: "Modern Apartment in Downtown",
    location: "Downtown, Tunis",
    price: 350000,
    propertyType: "apartment",
    bedrooms: 2,
    bathrooms: 1,
    area: 85,
    features: ["balcony", "elevator", "parking"],
    image: "assets/apartment1.jpg"
  },
  {
    id: 2,
    title: "Luxury Villa with Pool",
    location: "La Marsa, Tunis",
    price: 950000,
    propertyType: "villa",
    bedrooms: 5,
    bathrooms: 3,
    area: 320,
    features: ["pool", "garden", "garage", "security"],
    image: "assets/villa1.jpg"
  },
  {
    id: 3,
    title: "Cozy Family House",
    location: "Ras Jebel, Bizerte",
    price: 280000,
    propertyType: "house",
    bedrooms: 3,
    bathrooms: 2,
    area: 150,
    features: ["garden", "garage"],
    image: "assets/house1.jpg"
  },
  {
    id: 4,
    title: "Commercial Space for Rent",
    location: "Les Berges du Lac, Tunis",
    price: 450000,
    propertyType: "commercial",
    area: 200,
    features: ["parking", "security", "elevator"],
    image: "assets/commercial1.jpg"
  },
  {
    id: 5,
    title: "Waterfront Apartment",
    location: "Gammarth, Tunis",
    price: 550000,
    propertyType: "apartment",
    bedrooms: 3,
    bathrooms: 2,
    area: 120,
    features: ["sea view", "pool", "gym", "security"],
    image: "assets/apartment2.jpg"
  }
];

// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function() {
  // Initialize the page
  renderProperties(properties);
  setupEventListeners();
});

// Set up event listeners for search and filters
function setupEventListeners() {
  // Search button click
  document.getElementById('searchBtn').addEventListener('click', performSearch);
  
  // Enter key in search input
  document.getElementById('propertySearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      performSearch();
    }
  });
  
  // Filter change events
  document.getElementById('priceRange').addEventListener('change', performSearch);
  document.getElementById('propertyType').addEventListener('change', performSearch);
  document.getElementById('bedrooms').addEventListener('change', performSearch);
  
  // Reset filters
  document.getElementById('resetFilters').addEventListener('click', resetFilters);
}

// Perform search based on input and filters
function performSearch() {
  const searchTerm = document.getElementById('propertySearch').value.toLowerCase();
  const priceRange = document.getElementById('priceRange').value;
  const propertyType = document.getElementById('propertyType').value;
  const bedrooms = document.getElementById('bedrooms').value;
  
  // Filter properties based on criteria
  const filteredProperties = properties.filter(property => {
    // Search term matching
    const matchesSearch = 
      !searchTerm || // If search term is empty, consider it a match
      property.title.toLowerCase().includes(searchTerm) || 
      property.location.toLowerCase().includes(searchTerm) ||
      (property.features && property.features.some(feature => feature.toLowerCase().includes(searchTerm)));
    
    // Price range filtering
    let matchesPrice = true;
    if (priceRange) {
      if (priceRange === '1000000+') {
        matchesPrice = property.price >= 1000000;
      } else {
        const [min, max] = priceRange.split('-').map(Number);
        matchesPrice = property.price >= min && (max ? property.price <= max : true);
      }
    }
    
    // Property type filtering
    const matchesType = !propertyType || property.propertyType === propertyType;
    
    // Bedrooms filtering
    const matchesBedrooms = !bedrooms || (property.bedrooms && property.bedrooms >= parseInt(bedrooms));
    
    return matchesSearch && matchesPrice && matchesType && matchesBedrooms;
  });
  
  // Render the filtered results
  renderProperties(filteredProperties);
  
  // Add animation effect to show filtering is complete
  const container = document.querySelector('.search-container');
  container.classList.add('filter-applied');
  // setTimeout to remove the class after animation
  setTimeout(() => container.classList.remove('filter-applied'), 500);
}

// Reset all filters and search
function resetFilters() {
  document.getElementById('propertySearch').value = '';
  document.getElementById('priceRange').value = '';
  document.getElementById('propertyType').value = '';
  document.getElementById('bedrooms').value = '';
  
  // Render all properties
  renderProperties(properties);
  
  // Add animation to show reset is complete
  const container = document.querySelector('.search-container');
  container.style.animation = 'pulse 0.5s';
  setTimeout(() => container.style.animation = '', 500);
}

// Render properties to the container
function renderProperties(propertiesToRender) {
  const container = document.getElementById('propertiesContainer');
  
  // Check if container exists
  if (!container) {
    console.error('Properties container not found! Make sure you have an element with id="propertiesContainer"');
    return;
  }
  
  // Clear the container
  container.innerHTML = '';
  
  if (propertiesToRender.length === 0) {
    // Show "no results" message
    container.innerHTML = `
      <div class="no-results">
        <i class="fa-solid fa-house-circle-xmark"></i>
        <h3>No properties match your search criteria</h3>
        <p>Try adjusting your filters or search terms</p>
      </div>
    `;
    return;
  }
  
  // Add each property card
  propertiesToRender.forEach(property => {
    const propertyCard = document.createElement('div');
    propertyCard.className = 'property-card home-card';
    
    // Use a default image if the property image is not found
    const imgSrc = property.image || 'assets/images.jpeg';
    
    // Format price with commas
    const formattedPrice = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      maximumFractionDigits: 0
    }).format(property.price);
    
    // Generate features list
    const featuresList = property.features ? 
      property.features.map(feature => `<span class="feature-tag">${feature}</span>`).join('') : '';
    
    propertyCard.innerHTML = `
      <div class="property-image">
        <img src="${imgSrc}" alt="${property.title}" onerror="this.src='assets/images.jpeg';">
        <span class="property-price">${formattedPrice}</span>
      </div>
      <div class="property-details home-info">
        <h3>${property.title}</h3>
        <p class="property-location">
          <i class="fa-solid fa-location-dot"></i> ${property.location}
        </p>
        <div class="property-specs">
          ${property.bedrooms ? `<span><i class="fa-solid fa-bed"></i> ${property.bedrooms} bd</span>` : ''}
          ${property.bathrooms ? `<span><i class="fa-solid fa-bath"></i> ${property.bathrooms} ba</span>` : ''}
          <span><i class="fa-solid fa-vector-square"></i> ${property.area} m²</span>
        </div>
        <div class="property-features">
          ${featuresList}
        </div>
        <div class="rating">
          ${getRandomRating()}
        </div>
        <button class="view-property">View Details</button>
      </div>
    `;
    
    container.appendChild(propertyCard);
  });
  
  // Add click event for view details buttons
  document.querySelectorAll('.view-property').forEach(button => {
    button.addEventListener('click', function() {
      window.location.href = "newDetailsHouse.html";
    });
  });
}

// Helper function to generate random ratings (just for demo purposes)
function getRandomRating() {
  const ratings = ["★★★☆☆", "★★★★☆", "★★★★★"];
  return ratings[Math.floor(Math.random() * ratings.length)];
}

// Add CSS styling for the property cards
const style = document.createElement('style');
style.textContent = `
  @keyframes pulse {
    0% { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
    50% { box-shadow: 0 5px 30px rgba(0, 123, 255, 0.4); }
    100% { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
  }
  
  .filter-applied {
    animation: pulse 0.5s;
  }
  
  #propertiesContainer {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }
  
  .property-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
  }
  
  .property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  }
  
  .property-image {
    position: relative;
    height: 200px;
    overflow: hidden;
  }
  
  .property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
  }
  
  .property-card:hover .property-image img {
    transform: scale(1.05);
  }
  
  .property-price {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 123, 255, 0.9);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
  }
  
  .property-details {
    padding: 15px;
  }
  
  .property-details h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #333;
  }
  
  .property-location {
    color: #666;
    margin-bottom: 15px;
  }
  
  .property-specs {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    color: #555;
  }
  
  .property-features {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
  }
  
  .feature-tag {
    background: #f0f7ff;
    color: #0056b3;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 12px;
  }
  
  .view-property {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
    margin-top: 10px;
  }
  
  .view-property:hover {
    background: #0056b3;
  }
  
  .no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #6c757d;
  }
  
  .no-results i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #dee2e6;
  }
`;

document.head.appendChild(style);