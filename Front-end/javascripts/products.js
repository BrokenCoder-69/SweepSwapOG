const API_URL = "http://127.0.0.1:8000/api";
const token = localStorage.getItem("auth_token");






// Fetch all products
async function fetchProducts() {
  const res = await fetch(`${API_URL}/products`);
  const data = await res.json();
  let html = "";
  data.forEach(p => {
  const currentUserId = localStorage.getItem("user_id"); // store user_id on login
  const isOwner = token && currentUserId == p.user_id;
  // console.log(p.images);
  html += `
        <div class="col-md-4">
        <div class="card shadow-sm">
            ${p.images ? `<img src="http://127.0.0.1:8000${p.images}" class="card-img-top" alt="${p.name}">` : ""}


            


            <div class="card-body">
            <h5>${p.name}</h5>
            <p>${p.description.substring(0,50)}...</p>



            <p>Sold by: ${p.user ? p.user.name : "Unknown Seller"}</p>




            <p><b>$${p.price}</b></p>
            <a href="view.html?id=${p.id}" class="btn btn-primary btn-sm">View</a>
            ${isOwner ? `
                <a href="edit.html?id=${p.id}" class="btn btn-warning btn-sm">Edit</a>
                <button onclick="deleteProduct(${p.id})" class="btn btn-danger btn-sm">Delete</button>
            ` : ""}
            </div>
        </div>
        </div>
    `;
    });
  document.getElementById("product-list").innerHTML = html;
}


// <!-- porer to see the seller information -->

    async function fetchProduct(id) {
    try {
        const res = await fetch(`${API_URL}/products/${id}`);

        if (!res.ok) throw new Error("Product not found");
        const p = await res.json();
        document.getElementById("product-name").textContent = p.name || "Unnamed Product";

        document.getElementById("product-description").textContent = p.description || "No description available.";
        
        document.getElementById("product-category").textContent = `Category: ${p.category || "N/A"}`;

        document.getElementById("product-condition").textContent = `Condition: ${p.is_used ? "Used" : "New"}`;

        document.getElementById("product-price").textContent = `Price: $${p.price || "N/A"}`;

        // document.getElementById("product-seller").textContent = `Sold by: ${p.seller_name || "Unknown Seller"}`;

        document.getElementById("product-seller").textContent = `Sold by: ${p.user ? p.user.name : "Unknown Seller"}`;


    } catch (error) {
        console.error("Error fetching product:", error);
        document.getElementById("product-details").innerHTML = `
        <p class="text-danger">Failed to load product details. Please try again later.</p>
        `;
    }
    }









// Add product
// Add product
document.getElementById("addProductForm")?.addEventListener("submit", async e => {
  e.preventDefault();

  // Create FormData object to handle file and text fields
  const formData = new FormData();
  formData.append("name", document.getElementById("name").value);
  formData.append("description", document.getElementById("description").value);
  formData.append("category", document.getElementById("category").value);
  formData.append("is_used", document.getElementById("is_used").value);
  formData.append("usage_duration", document.getElementById("usage_duration").value || "");
  formData.append("price", document.getElementById("price").value);


  // Get the image file
  const imageFile = document.getElementById("images").files[0];
  if (imageFile) {
    formData.append("images", imageFile); // Match the field name expected by the backend
  }

  try {
    const res = await fetch(`${API_URL}/products`, {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${token}`,
        // Do NOT set Content-Type; browser sets it to multipart/form-data automatically
      },
      body: formData
    });

    const data = await res.json();
    if (data.success) {
      alert("Product added!");
      window.location = "list.html";
    } else {
      alert("Failed to add product: " + (data.message || "Unknown error"));
    }
  } catch (error) {
    console.error("Error adding product:", error);
    alert("An error occurred while adding the product. Please try again.");
  }
});





// Load product for editing
async function loadProductForEdit(id) {
  const res = await fetch(`${API_URL}/products/${id}`);
  const p = await res.json();
  document.getElementById("name").value = p.name;
  document.getElementById("description").value = p.description;
  document.getElementById("category").value = p.category;
  document.getElementById("is_used").value = p.is_used ? 1 : 0;
  document.getElementById("usage_duration").value = p.usage_duration || "";
  document.getElementById("price").value = p.price;

  document.getElementById("editProductForm").addEventListener("submit", async e => {
    e.preventDefault();
    const res = await fetch(`${API_URL}/products/${id}`, {
      method: "PUT",
      headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        name: document.getElementById("name").value,
        description: document.getElementById("description").value,
        category: document.getElementById("category").value,
        is_used: document.getElementById("is_used").value,
        usage_duration: document.getElementById("usage_duration").value,
        price: document.getElementById("price").value
      })
    });
    const data = await res.json();
    if (data.success) {
      alert("Product updated!");
      window.location = "list.html";
    }
  });
}





// Delete product
async function deleteProduct(id) {
  if (!confirm("Are you sure?")) return;
  const res = await fetch(`${API_URL}/products/${id}`, {
    method: "DELETE",
    headers: { "Authorization": `Bearer ${token}` }
  });
  const data = await res.json();
  if (data.success) {
    alert("Product deleted");
    location.reload();
  }
}
















// Send message to seller
document.getElementById("messageForm")?.addEventListener("submit", async e => {
  e.preventDefault();
  if (!token) {
    alert("Please log in to send messages.");
    return;
  }
  const productId = new URLSearchParams(window.location.search).get("id");
  const message = document.getElementById("message").value;
  try {
    const res = await fetch(`${API_URL}/messages`, {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ product_id: productId, message })
    });
    const data = await res.json();
    if (data.success) {
      alert("Message sent!");
      document.getElementById("message").value = "";
    } else {
      alert("Failed to send message: " + (data.message || "Unknown error"));
    }
  } catch (error) {
    console.error("Error sending message:", error);
    alert("An error occurred while sending the message. Please try again.");
  }
});