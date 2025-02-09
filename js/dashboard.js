document.addEventListener("DOMContentLoaded", function () {
    const assistantDropdown = document.getElementById("assistant-dropdown");

    // Load the saved assistant ID from localStorage if it exists
    const savedAssistantId = localStorage.getItem("selectedAssistantId");
    console.log("savedAssistantId:", savedAssistantId); // Debugging

    // Check if assistant data is available
    if (typeof assistantData !== "undefined" && assistantData.length > 0) {
        // Populate the dropdown with assistant IDs
        assistantData.forEach(function (assistantId) {
            const option = document.createElement("option");
            option.value = assistantId;
            option.textContent = assistantId; // Format as needed
            assistantDropdown.appendChild(option);
        });

        // Set the dropdown to the saved assistant ID after options are added
        if (savedAssistantId) {
            assistantDropdown.value = savedAssistantId;
            console.log("Dropdown value set to:", assistantDropdown.value); // Debugging
        }
    } else {
        console.error("No assistants found for the current user.");
    }

    // Listen for dropdown selection changes
    assistantDropdown.addEventListener("change", function () {
        const selectedAssistantId = this.value;

        // Store the selected ID in localStorage to persist after reload
        localStorage.setItem("selectedAssistantId", selectedAssistantId);

        // Trigger updates or calculations on the page
        console.log("Selected Assistant ID:", selectedAssistantId);

        // Send the selected assistant_id to the server via AJAX
        fetch(ajax_object.ajaxurl, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                action: "save_assistant_id", // Action hook in PHP
                assistant_id: selectedAssistantId,
            }),
        })
            .then(response => response.json())
            .then(data => {
                console.log("Server response:", data);

                // If saving was successful, reload the page
                if (data.success) {
                    location.reload(); // Reloads the current page
                } else {
                    console.error("Error saving assistant ID:", data.data);
                }
            })
            .catch(error => console.error("Error saving assistant ID:", error));
    });
});
