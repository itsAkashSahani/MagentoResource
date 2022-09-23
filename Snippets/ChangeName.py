from os import rename
from os import listdir
from os.path import splitext

# Current directory script is being run in
# You can change this to any path you want
path_to_folder = "."

for f in listdir(path_to_folder):
    if f.endswith(".JPG"):
        name, ext = splitext(f)
        rename(f, name + ext)
