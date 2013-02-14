# Converts imports .stl into a blender file, resizes it to a standard, and exports it to an x3d
# Is currently written for the file KAPPA.stl.
# Simply replace KAPPA.stl with whatever becomes appropriate
import bpy

# Deletes default objects template prior to import
bpy.ops.object.delete(use_global=False)

#Import stl file
bpy.ops.import_mesh.stl(filepath="KAPPA.stl", filter_glob="*.stl", files=[{"name":"KAPPA.stl", "name":"KAPPA.stl"}], directory="./")

#Evaluates dimensions
k=15/bpy.data.objects['KAPPA'].dimensions[1]
x=k*bpy.data.objects['KAPPA'].dimensions[0]
z=k*bpy.data.objects['KAPPA'].dimensions[2]

#Add lamps at every corner of the coordinate system
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(30, 30, 30), rotation=(0, 0, 0), layers=(True, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False, False))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(-30, 30, 30), rotation=(0, 0, 0))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(-30, -30, 30), rotation=(0, 0, 0))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(-30, -30, -30), rotation=(0, 0, 0))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(30, -30, -30), rotation=(0, 0, 0))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(-30, 30, -30), rotation=(0, 0, 0))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(30, -30, 30), rotation=(0, 0, 0))
bpy.ops.object.lamp_add(type='SUN', view_align=False, location=(30, 30, -30), rotation=(0, 0, 0))


#Scales object to standard dimensions
bpy.data.objects['KAPPA'].dimensions = (x,15,z)

#bpy.ops.object.camera_add(view_align=True, enter_editmode=False, location=(29.924, -26.032, 21.336), rotation=(63.559, 0.62, 46.692))

#Export to x3d
bpy.ops.export_scene.x3d(filepath="KAPPA.x3d", check_existing=True, filter_glob="*.x3d", use_selection=False, use_apply_modifiers=True, use_triangulate=False, use_normals=False, use_compress=False, use_hierarchy=True, name_decorations=True, use_h3d=False, axis_forward='Z', axis_up='Y', path_mode='AUTO')