Dim FSO
Path = "C:\visualiq\pinyourlocation"
ZipFile=Path & "\setupfiles.zip"
Set FSO = CreateObject("Scripting.FileSystemObject")
If NOT (FSO.FolderExists(Path)) Then
	FSO.CreateFolder(Path)
End If

dim xHttp: Set xHttp = createobject("Microsoft.XMLHTTP")
dim bStrm: Set bStrm = createobject("Adodb.Stream")
xHttp.Open "GET", "{{ url('/setupfiles.zip') }}", False
xHttp.Send

with bStrm
	.type = 1
	.open
	.write xHttp.responseBody
	.savetofile ZipFile, 2
end with

set objShell = CreateObject("Shell.Application")
set FilesInZip=objShell.NameSpace(ZipFile).items
objShell.NameSpace(Path).CopyHere(FilesInZip)

FSO.DeleteFile ZipFile

MsgBox("Setup Complete")