#!/bin/sh

# Define the ambient light range and lightness range
AMBIENT_MIN=0
AMBIENT_MAX=100
LIGHTNESS_MIN=1
LIGHTNESS_MAX=20

change_status="no_change"

# Get the current front light intensity
current_level=$(lipc-get-prop com.lab126.powerd flIntensity)

# Get the ambient light intensity in lux
ambient_light=$(lipc-get-prop com.lab126.powerd alsLux)

# Function to calculate lightness level with faster increase at lower levels
calculate_lightness() {
    ambient_light=$1

    # Calculate the normalized value (0 to 1) using awk
    normalized_value=$(awk "BEGIN {print $ambient_light / $AMBIENT_MAX}")

    # Calculate lightness level using the new formula with awk
    lightness=$(awk "BEGIN {print $LIGHTNESS_MIN + ($LIGHTNESS_MAX - $LIGHTNESS_MIN) * ($normalized_value ^ 0.5)}")

    # Round to nearest integer using awk
    lightness=$(awk "BEGIN {print int($lightness + 0.5)}")

    # Ensure lightness is within the range of LIGHTNESS_MIN to LIGHTNESS_MAX
    if [ "$lightness" -lt "$LIGHTNESS_MIN" ]; then
        lightness=$LIGHTNESS_MIN
    elif [ "$lightness" -gt "$LIGHTNESS_MAX" ]; then
        lightness=$LIGHTNESS_MAX
    fi

    echo "$lightness"
}

frontlight_level=$(calculate_lightness $ambient_light)

# Check if the new intensity is higher than the current intensity
if [ "$frontlight_level" -gt "$current_level" ]; then
    # Set the front light intensity
    lipc-set-prop com.lab126.powerd flIntensity "$frontlight_level"
    change_status="increased"
fi

# Print the JSON output in one line
echo "{\"status\": \"$change_status\", \"previous_level\": $current_level, \"new_level\": $frontlight_level, \"ambient_light\": $ambient_light}"
