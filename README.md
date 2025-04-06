# 🛠️ SupportMCP

SupportMCP is a lightweight ticket management system that includes:

- ✅ A simple CRUD for managing support tickets
- 💬 A WebSocket-based AI-powered chat using **Gemini AI**
- 🧑‍💼 Role-based system for **clients** and **employees**
  
---

## ✨ Features

### 🎫 Ticket Management (CRUD)
- Employees can **create**, **read**, **update**, and **delete** tickets.
- Tickets include information like:
  - Type: `bug`, `feature_request`, `question`, or `other`
  - Status: open, assigned, resolved
  - Priority and system info
- Employees can **assign themselves** to tickets and **write a resolution message** for the client.
- Timestamps like `assigned_at` and `resolved_at` are automatically handled.

### 🤖 AI Chat Assistant (Gemini AI)
- Real-time WebSocket chat interface between **client** and **Gemini AI**
- Clients can:
  - **Create users** via the chat
  - **Submit tickets** (e.g., bug reports, feature requests)
  - **Ask questions**
  - **Receive the final resolution** when a ticket is resolved

### 👥 User Roles
- **Clients**: Interact only through the chat to create and view tickets.
- **Employees**: Use the admin panel to manage tickets, assign them, and provide resolutions.

---

## 🧪 Tech Stack

| Layer        | Tech                      |
|--------------|---------------------------|
| Backend      | Laravel                   |
| Frontend     | Vue 3 + Vite (via Breeze) |
| AI Assistant | Gemini (via WebSocket)    |
| Auth         | Laravel Breeze (sanctum)  |
| Database     | MySQL (or any Laravel DB) |

---

## 🖼️ System Screenshots
### User interaction with Chat
![image](https://github.com/user-attachments/assets/aca2931f-20a8-433d-92d6-73fd97e5dc4c)

![image](https://github.com/user-attachments/assets/aaf64c0b-b119-4a49-a6a9-fc7c4d9ea8f5)

![image](https://github.com/user-attachments/assets/8853b988-f347-46d1-80b0-e22e7e732c38)

![image](https://github.com/user-attachments/assets/fd7b79a5-a968-446d-93cb-861b74a72426)
### After ticket creation:
![image](https://github.com/user-attachments/assets/ad0e183a-7787-4152-a7fc-077a02356045)
### After ticket resolution:
![image](https://github.com/user-attachments/assets/f7733427-d7c4-449a-b9d0-69789b189322)
