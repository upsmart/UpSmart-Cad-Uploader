#!/bin/bash

blender -b -P cad.import.py

# Convert from x3d to x3dom
aopt -i KAPPA.x3d -N KAPPA.html

