import httpx
import asyncio

async def register_client_or_get(
    # The full name of the client
    name: str,
    # The email address of the client
    email: str,
    # The phone number of the client
    number: str,
    # The extension of the phone number of the client
    extension: str,
    # The department of the client
    department: str,
):
    """Register a new client or get the details of an existing client.
    If the client already exists, return their details.
    If the client does not exist, register them and return their details.

    Args:
        name (str): The full name of the client.
        email (str): The email address of the client.
        number (str): The phone number of the client.
        extension (str): The extension of the phone number of the client.
        department (str): The department of the client.
    Returns:
        dict: The details of the client.
    """
    client_details = None
    async with httpx.AsyncClient() as client:
        response = await client.post(
            "http://localhost:8000/api/users",
            json={
                "name": name,
                "email": email,
                "number": number,
                "extension": extension,
                "department": department,
            },
        )
        if response.status_code == 200:
            client_details = response.json()
        else:
            client_details = {"error": "Failed to register or get client"}
    return client_details

async def get_systems():
    """Get the list of systems.
    Args:
        None
    Returns:
        list: The list of systems.
    """
    systems = None
    async with httpx.AsyncClient() as client:
        response = await client.get("http://localhost:8000/api/systems")
        if response.status_code == 200:
            systems = response.json()
        else:
            raise Exception("Failed to get systems")
    return systems


async def create_ticket(
    # The client ID
    client_id: int,
    # The system ID
    system_id: int,
    # The title of the ticket
    issue: str,
    # The description of the ticket
    description: str,
    # The priority of the ticket
    priority: str,
    # The category of the ticket
    category: str,
    # The score of the interaction with the AI support system
    score: int,
):
    """The function creates a ticket for the client.
    Args:
        client_id (int): The ID of the client.
        system_id (int): The ID of the system.
        issue (str): The title of the ticket.
        description (str): The description of the ticket.
        priority (str): The priority of the ticket can be low, medium or high.
        category (str): The category of the ticket bug, feature_request, question or other.
        score (int): The score of the interaction with the AI support system.
    Returns:
        It returns the ticket details. After creating the ticket
    """
    ticket_details = None
    async with httpx.AsyncClient() as client:
        response = await client.post(
            "http://localhost:8000/api/tickets",
            json={
                "client_id": client_id,
                "system_id": system_id,
                "issue": issue,
                "description": description,
                "priority": priority,
                "category": category,
                "score": score,
                "status": "open",
            },
        )
        if response.status_code == 200:
            ticket_details = response.json()
        else:
            ticket_details = {"error": "Failed to create ticket"}
    return ticket_details

async def get_ticket(ticket_id: int):
    """Get the details of a ticket.
    
    Args:
        ticket_id (int): The ID of the ticket to retrieve.

    Returns:
        dict: The details of the ticket.
    """
    ticket_details = None
    async with httpx.AsyncClient() as client:
        response = await client.get(f"http://localhost:8000/api/tickets/{ticket_id}")
        if response.status_code == 200:
            ticket_details = response.json()
        else:
            raise Exception("Failed to get ticket details")
    return ticket_details