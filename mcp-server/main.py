from mcp.server.fastmcp import FastMCP
import httpx
import asyncio

mcp = FastMCP("SuricataMCP", dependencies=[
    "httpx",
    "asyncio",
])

@mcp.tool()
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
    """
    Register a new client or get the details of an existing client.
    If the client already exists, return their details.
    If the client does not exist, register them and return their details.
    """
    client_details = None
    async with httpx.AsyncClient() as client:
        response = await client.post(
            "http://support/clients",
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
            raise Exception("Failed to register or get client details")
    return client_details

@mcp.tool()
async def get_systems():
    """
    Get the list of systems.
    """
    systems = None
    async with httpx.AsyncClient() as client:
        response = await client.get("http://support/systems")
        if response.status_code == 200:
            systems = response.json()
        else:
            raise Exception("Failed to get systems")
    return systems


@mcp.tool()
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
    """
    The function creates a ticket for the client.
    It takes the client ID, system ID, issue title, description,
    priority, category, and score as input.
    It returns the ticket details. After creating the ticket
    """
    ticket_details = None
    async with httpx.AsyncClient() as client:
        response = await client.post(
            "http://support/tickets",
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
            raise Exception("Failed to create ticket")
    

@mcp.tool()
async def get_ticket(ticket_id: int):
    """
    Get the details of a ticket.
    """
    ticket_details = None
    async with httpx.AsyncClient() as client:
        response = await client.get(f"http://support/tickets/{ticket_id}")
        if response.status_code == 200:
            ticket_details = response.json()
        else:
            raise Exception("Failed to get ticket details")
    return ticket_details


mcp.start()