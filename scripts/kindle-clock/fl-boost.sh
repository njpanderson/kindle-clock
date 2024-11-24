#!/bin/sh

# Get the current front light intensity
current_level=$(lipc-get-prop com.lab126.powerd flIntensity)

# Get the ambient light intensity in lux
ambient_light=$(lipc-get-prop com.lab126.powerd alsLux)

# Calculate the front light level based on the ambient light
# Map 0-100 lux to 1-10 front light levels
if [ "$ambient_light" -le 0 ]; then
    frontlight_level=1
elif [ "$ambient_light" -ge 100 ]; then
    frontlight_level=10
else
    frontlight_level=$(( (ambient_light * 9 / 100) + 1 ))
fi

# Ensure front light level is at least 1
if [ "$frontlight_level" -lt 1 ]; then
    frontlight_level=1
fi

change_status="no_change"

# Check if the new intensity is higher than the current intensity
if [ "$frontlight_level" -gt "$current_level" ]; then
    # Set the front light intensity
    lipc-set-prop com.lab126.powerd flIntensity "$frontlight_level"
    change_status="increased"
fi

# Print the JSON output in one line
echo "{\"status\": \"$change_status\", \"previous_level\": $current_level, \"new_level\": $frontlight_level, \"ambient_light\": $ambient_light}"
