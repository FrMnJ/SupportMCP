from google.genai import types

register_client_or_get_func = {
    "name": "register_client_or_get",
    "description": "Register a new client or get the details of an existing client.",
    "parameters": {
        "type": "object",
        "properties": {
            "name": {
                "type": "string",
                "description": "The full name of the client",
            },
            "email": {
                "type": "string",
                "description": "The email address of the client",
            },
            "number": {
                "type": "string",
                "description": "The phone number of the client",
            },
            "extension": {
                "type": "string",
                "description": "The extension of the phone number of the client",
            },
            "department": {
                "type": "string",
                "description": "The department of the client",
            },
        },
        "required": ["name", "email", "number", "extension", "department"],
    }
}

get_systems_func = {
    "name": "get_systems",
    "description": "Get the list of systems.",
    "parameters": {
        "type": "object",
        "properties": {},
        "required": [],
    }
}

create_ticket_func = {
    "name": "create_ticket",
    "description": "Create a new ticket.",
    "parameters": {
        "type": "object",
        "properties": {
            "client_id": {
                "type": "integer",
                "description": "The client ID",
            },
            "system_id": {
                "type": "integer",
                "description": "The system ID",
            },
            "issue": {
                "type": "string",
                "description": "The title of the ticket",
            },
            "description": {
                "type": "string",
                "description": "The description of the ticket",
            },
            "priority": {
                "type": "string",
                "description": "The priority of the ticket can be low, medium, or high",
            },
            "category": {
                "type": "string",
                "description": "The category of the ticket can be bug, feature_request, question, or other",
            },
            "score": {
                "type": "integer",
                "description": "The score of the interaction with the AI localhost system",
            }
        },
        "required": ["client_id", "system_id", "issue", "description", "priority", "category", "score"],
    }
}

get_ticket_func = {
    "name": "get_ticket",
    "description": "Get the details of a ticket.",
    "parameters": {
        "type": "object",
        "properties": {
            "ticket_id": {
                "type": "integer",
                "description": "The ticket ID",
            },
        },
        "required": ["ticket_id"],
    }
}

suricata = types.Tool(function_declarations=[
    register_client_or_get_func,
    get_systems_func,
    create_ticket_func,
    get_ticket_func,
])