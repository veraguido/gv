#!/bin/bash
echo "Please select the option: "
echo "--------------------------"
echo "1 - Flush all"
echo "2 - Flush key"
echo "3 - Flush config"
echo "4 - Flush routes"
echo "5 - Exit"
echo "--------------------------"



while true; do
read -rsn1 input
if [ "$input" = "1" ]; then
    redis-cli -r 1 flushall
    echo "redis cache flushed"
    exit 1
fi
if [ "$input" = "2" ]; then
    echo "Flush specific key please input key"
    while true; do
    read varname
    redis-cli KEYS $varname | xargs redis-cli del
    exit 1
    done
fi
if [ "$input" = "3" ]; then
    echo "Flush config key"
    redis-cli KEYS "gv_config" | xargs redis-cli del
    exit 1
fi
if [ "$input" = "4" ]; then
    echo "Flush routes key"
    redis-cli KEYS "gv_routes" | xargs redis-cli del
    exit 1
fi
if [ "$input" = "5" ]; then
    echo "Exit"
    exit 1
fi
done


