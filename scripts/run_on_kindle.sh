#!/bin/bash

# Define the username — set to root as this is what everyone seems to use for sshing into the kindle
USERNAME="root"
SSH_KEY="$(dirname "$0")/kindle_id_rsa" # Replace with the actual path to your SSH private key

# Check if the correct number of arguments is provided
if [ "$#" -lt 2 ]; then
    echo "Usage: $0 <hostname> <command> [args...]"
    exit 1
fi

# Assign the first argument to HOSTNAME and the rest to COMMAND
HOSTNAME=$1
shift  # Shift the arguments to the left, so $@ now contains the command and its arguments

# Run the command on the remote host using SSH
ssh -i "${SSH_KEY}" "${USERNAME}@${HOSTNAME}" "$@"

# Check if the command was successful
if [ $? -eq 0 ]; then
    echo "Command executed successfully."
else
    echo "Failed to execute command."
fi
