from flask import Flask, jsonify, request
import uuid
import os
import signal
from flask_cors import CORS  # Import CORS

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

def get_mac_address():
    """Retrieve the MAC address of the machine."""
    mac = hex(uuid.getnode()).replace("0x", "").upper()
    mac = ":".join(mac[i:i+2] for i in range(0, 12, 2))
    return mac

@app.route("/get-mac-address", methods=["GET"])
def mac_address():
    """Return the MAC address as a JSON response."""
    mac_address = get_mac_address()
    return jsonify({"mac_address": mac_address})

@app.route("/shutdown", methods=["POST"])
def shutdown():
    """Shutdown the Flask server."""
    shutdown_func = request.environ.get('werkzeug.server.shutdown')
    if shutdown_func is None:
        raise RuntimeError("Not running with the Werkzeug Server")
    shutdown_func()
    return "Server shutting down..."

if __name__ == "__main__":
    app.run(port=5000, host="0.0.0.0")  # Make sure the server listens on all interfaces
