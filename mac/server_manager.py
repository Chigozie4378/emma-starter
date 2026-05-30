import subprocess
import os
from flask import Flask, jsonify, request

app = Flask(__name__)

@app.route("/start-server", methods=["POST"])
def start_server():
    """Start the Flask server if not already running."""
    if not is_flask_running():
        subprocess.Popen(["python", os.path.join(os.path.dirname(__file__), "mac_server.py")], shell=True)
        return jsonify({"status": "Server started"}), 200
    return jsonify({"status": "Server already running"}), 200

def is_flask_running():
    """Check if Flask server is running."""
    import requests
    try:
        response = requests.get("http://127.0.0.1:5000/get-mac-address", timeout=2)
        return response.status_code == 200
    except requests.ConnectionError:
        return False

if __name__ == "__main__":
    app.run(port=4000, host="0.0.0.0")  # Flask Starter Script runs on port 4000
