import sys

name = "Damian"
album = "57938"
version = sys.version.split()[0]
location = sys.executable

print(f"Hello {name} ({album}). This environment is using Python version {version} at location {location}.")