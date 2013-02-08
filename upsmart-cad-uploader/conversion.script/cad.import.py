# Converts imports .stl into a blender file, resizes it to a standard, and exports it to an x3d
# Is currently written for the file KAPPA.stl.
# Simply replace KAPPA.stl with whatever becomes appropriate
import bpy
import sys

orgFile = sys.argv[5]
uPath = sys.argv[6]
stlFile = orgFile + '.stl'
x3dFile = orgFile + '.x3d'
imgfile = orgFile + '.png'
	
# Deletes default objects template prior to import
bpy.ops.object.delete(use_global=False)

#Import stl file
bpy.ops.import_mesh.stl(filepath= uPath + stlFile)
#files=[{"name":"KAPPA.stl"}], directory="/home/sam/Downloads"
#Evaluates dimensions

k=15/bpy.data.objects[orgFile].dimensions[1]
x=k*bpy.data.objects[orgFile].dimensions[0]
z=k*bpy.data.objects[orgFile].dimensions[2]

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
bpy.data.objects[orgFile].dimensions = (x,15,z)

#bpy.ops.object.camera_add(view_align=True, enter_editmode=False, location=(29.924, -26.032, 21.336), rotation=(63.559, 0.62, 46.692))

#Export to x3d
bpy.ops.export_scene.x3d(filepath= uPath + x3dFile, check_existing=True, use_selection=False, use_apply_modifiers=True, use_triangulate=False, use_normals=False, use_compress=False, use_hierarchy=True, name_decorations=True, use_h3d=False, axis_forward='Z', axis_up='Y', path_mode='AUTO')



#Export as image
#bpy.ops.image.save_as(copy=True, filepath=uPath + imgFile, check_existing=True, filter_blender=False, filter_image=True, filter_movie=True, filter_python=False, filter_font=False, filter_sound=False, filter_text=False, filter_btx=False, filter_collada=False, filter_folder=True, filemode=9, path_mode='AUTO', display_type='FILE_DEFAULTDISPLAY')

#bpy.ops.object.delete(use_global=False)

#Import stl file
#bpy.ops.import_mesh.stl(filepath= uPath + stlFile)
#bpy.ops.render.render(animation=False, write_still=False, layer="", scene="")
#bpy.data.images.save_as(copy=True, file_format='PNG', filepath=uPath + imgFile, check_existing=True, filter_blender=False, filter_image=True, filter_movie=True, filter_python=False, filter_font=False, filter_sound=False, filter_text=False, filter_btx=False, filter_collada=False, filter_folder=True, filemode=9, relative_path=TRUE, display_type='FILE_DEFAULTDISPLAY') 