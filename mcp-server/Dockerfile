FROM python:3.11.11-slim-bookworm

# Instala dependencias de Python
RUN python -m pip install --upgrade pip
RUN pip install mcp[cli] requests watchdog asyncio httpx

WORKDIR /mcp-server
COPY . .

EXPOSE 5000
CMD [ "watchmedo", "auto-restart", "--directory=.", "--pattern=*.py", "--", "python", "main.py" ]
