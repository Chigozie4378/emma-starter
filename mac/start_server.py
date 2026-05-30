import subprocess
import os
import requests

def is_server_running():
    try:
        response = requests.get("http://127.0.0.1:5000/get-mac-address", timeout=2)
        return response.status_code == 200
    except requests.ConnectionError:
        return False

def start_flask_server():
    script_path = os.path.join(os.path.dirname(__file__), "mac_server.py")
    subprocess.Popen(["python", script_path], shell=True)

if not is_server_running():
    start_flask_server()
