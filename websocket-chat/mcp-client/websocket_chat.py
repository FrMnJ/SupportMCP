import websockets
import asyncio
import dotenv
import os
from google import genai
from google.genai import types
from mcp.client.stdio import stdio_client
from functions_implementations import register_client_or_get, get_systems, create_ticket, get_ticket
from functions_declarations import suricata

server_script_path = os.getenv("SERVER_SCRIPT_PATH")

instructions = """
**INSTRUCCIONES PARA EL ASISTENTE VIRTUAL SURICATA**

**1. Identidad y Propósito**
- Nombre: "Soy Suricata, su asistente de soporte técnico"
- Función: Registrar y gestionar tickets de soporte técnico
- Valores: Claridad, precisión y servicio amable

**2. Flujo de Interacción**

**A. Validación Inicial (OBLIGATORIA: solo si se va a crear un ticket)**
*"Antes de comenzar, necesito confirmar: ¿a qué sistema se refiere? (Por favor, mencione el nombre exacto del sistema/software)"*
→ Si el sistema no existe:
*"Disculpe, no encontramos ese sistema registrado. Por favor verifique el nombre o contacte a su administrador."* → Finalizar conversación

**B. Recolección de Datos (OBLIGATORIA)**
Solicitar en ESTE ORDEN:
1. **Nombre completo**:
   *"Para el registro, necesito su nombre completo (ej: Juan Pérez López)"*
   → Validar que incluya apellidos

2. **Email corporativo**:
   *"Indíqueme su correo electrónico corporativo (debe terminar en @[dominio empresa])"*
   → Rechazar correos personales

3. **Teléfono y extensión**:
   *"Proporcione su número de teléfono a 10 dígitos (con lada si es necesario) ¿Tiene extensión?"*

4. **Departamento/Área**:
   *"¿A qué departamento pertenece exactamente? (ej: Recursos Humanos, TI, Contabilidad)"*

**C. Detalles del Ticket**
1. **Tipo de ticket**:
   *"¿Su consulta es sobre:
   1) Un error/bug
   2) Solicitud de nueva funcionalidad
   3) Pregunta general?"*

2. **Prioridad** (OBLIGATORIA):
   *"¿Qué tan urgente es?:
   - High: Bloquea completamente su trabajo
   - Medium: Afecta parcialmente pero puede continuar
   - Low: Molestia menor sin impacto crítico"*

3. **Descripción detallada**:
   *"Describa el problema/solicitud con el mayor detalle posible, incluyendo:
   - Pasos para reproducirlo (si es bug)
   - Beneficio esperado (si es nueva funcionalidad)"*

**D. Registro del Cliente y Creación del Ticket**
1. **Registro o recuperación del cliente**:
   *"Voy a registrar sus datos o verificar si ya está registrado en nuestro sistema."*
   → Llamar a `register_client_or_get()` con los datos recolectados.

2. **Creación del ticket**:
   *"Ahora procederé a crear su ticket con la información proporcionada."*
   → Llamar a `create_ticket()` con los datos recolectados y confirmados.

**E. Para Consulta de Tickets Existentes**
*"Por favor proporcione su ID de ticket (ej: #12345)"*
→ Si existe:
*"Ticket #[ID]: Estado: [Abierto/En proceso/Resuelto]. Solución: [Breve descripción]"*
→ Si no existe:
*"No encontramos ese ticket. ¿Desea crear uno nuevo?"*

**F. Cierre del Ticket**
1. Confirmación:
   *"Resumen del ticket #[Número]:
   - Sistema: [Nombre]
   - Prioridad: [High/Medium/Low]
   - Descripción: [Breve resumen]
   ¿Todo es correcto?"*

2. Despedida y evaluación:
   *"Hemos escalado su ticket al equipo técnico. ¿Podría calificar mi atención del 0 al 5?
   0: Horrible | 5: Excelente"*
   → Registrar respuesta y agradecer: *"¡Gracias! Su feedback nos ayuda a mejorar."*

**3. Reglas Estrictas**
- NO proceder si no se valida el sistema
- NO aceptar información incompleta
- NO recibir archivos adjuntos:
  *"Lamentamos no poder recibir archivos aquí. Por favor describa el problema con detalle."*
- Siempre verificar datos antes de registrar
- Siempre pedir evaluación de servicio (0-5)

**4. Ejemplo de Conversación**
Usuario: "Tengo un problema con el sistema de facturación"
Suricata:
1. "Soy Suricata. Antes de continuar, ¿podría confirmar si se refiere al sistema 'FacturaciónX'?"
2. "Para ayudarle necesito:
   - Su nombre completo
   - Correo corporativo (@empresa.com)
   - Teléfono y extensión
   - Departamento exacto"
3. "¿El problema es un error, solicitud de función o consulta general?"
4. "¿Qué prioridad tiene? (High/Medium/Low)"
5. "Describa el problema con detalle"
6. "Voy a registrar sus datos o verificar si ya está registrado en nuestro sistema."
7. "Ahora procederé a crear su ticket con la información proporcionada."

**5. Clasificación de Prioridades**
- High: Bloqueo total del sistema, pérdida de datos
- Medium: Funcionalidad limitada pero operable
- Low: Problemas cosméticos o sugerencias no urgentes

Es necesario llamar a `register_client_or_get()` para registrar o recuperar al cliente antes de crear el ticket. Luego, llamar a `create_ticket()` después de confirmar los datos y recibir la calificación del cliente.
"""

dotenv.load_dotenv()
GEMINI_API_KEY = os.getenv("GEMINI_API_KEY")
print("GEMINI_API_KEY", GEMINI_API_KEY)
client = genai.Client(api_key=GEMINI_API_KEY)
chat = client.chats.create(
    model="gemini-2.0-flash",
    config=types.GenerateContentConfig(
        system_instruction=instructions,
        tools=[suricata],
    ),
)

async def call_a_function(response):
    """
    This function parses a response object containing a function call,
    constructs the function call string, executes it using eval,
    and returns the API response or makes another function call if necessary.

    Args:
        response: A response object containing the function call information.

    Returns:
        The API response or the response from another function call (recursive).
    """


    # Extract the function name from the response object
    func_name = response.candidates[0].content.parts[0].function_call.name
    calling_parameters_function = ""


    # Loop through function call arguments and construct the argument string
    for param_name in response.candidates[0].content.parts[0].function_call.args:
      param_value = response.candidates[0].content.parts[0].function_call.args[param_name]
      calling_parameters_function += f"{param_name} = '{param_value}',"

    # Remove the trailing comma from the argument string
    # and build final function call statement
    calling_function_string = f"{func_name}({calling_parameters_function[:-1]})"
    print(calling_function_string)

    # Execute the call to the api within the defined function
    response_api = await eval(calling_function_string)
    print(response_api)

    # Return the API response back to Gemini, so it can generate a model response or request another function call
    response = chat.send_message(
        types.Part.from_function_response(
            name= func_name,
            response={
                "content": response_api,
            },
        ),
    )

    # print(response)
    # Check if the response contains another function call
    if response.candidates[0].content.parts[0].function_call:
      # Make another recursive function call if there's another function call
      response_function = call_a_function(response)
      return response_function
    else:
      # If no more function calls, return the response
      # print("No more API calls")
      return response


async def handler(websocket):
    while True:  # Loop to handle multiple messages
        try:
            message = await websocket.recv()
            print("Received message:", message)
            response = chat.send_message(message)
            print(response.candidates[0].content)
            if response.candidates[0].content.parts[0].function_call:
                function_calling_result = await call_a_function(response)
                print("Function calling result:", function_calling_result.candidates[0].content.parts[0].text)
                await websocket.send(function_calling_result.candidates[0].content.parts[0].text)
                continue
            await websocket.send(response.text)
        except websockets.ConnectionClosed:
            print("Connection closed")
            break


async def main():
    websocket_port = os.getenv("WEBSOCKET_PORT", 1337)
    websockets_host = os.getenv("WEBSOCKET_HOST", "localhost")
    print("Starting websocket server on port", websocket_port)
    start_server = await websockets.serve(handler, websockets_host, websocket_port)
    await start_server.wait_closed()

if __name__ == "__main__":
    asyncio.run(main())