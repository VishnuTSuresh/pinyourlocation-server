If Not WScript.Arguments.Named.Exists("elevate") Then
  MsgBox("This program removes legacy WorkFromHome app and installs the new one."& vbcrlf &"The installation will happen in the background."& vbcrlf &"All node.exe processes will be terminated.")
  CreateObject("Shell.Application").ShellExecute chr(34) & WScript.FullName & chr(34) _
    , chr(34) & WScript.ScriptFullName & chr(34) & " /elevate", "", "runas", 1
  WScript.Quit
End If

Path = "C:\visualiq\pinyourlocation"
ZipFile=Path & "\setupfiles.zip"
Token = "{{$token}}"

Dim FSO
Set FSO = CreateObject("Scripting.FileSystemObject")

'Here legacy wfh will be uninstalled

Dim oShell : Set oShell = CreateObject("WScript.Shell")
oShell.Run "taskkill /im node.exe", , True

LegacyPath = "C:\visualiq\wfh"
LegacyStartupPath = "C:\ProgramData\Microsoft\Windows\Start Menu\Programs\StartUp\WFH.lnk"

If (FSO.FolderExists(LegacyPath)) Then
	FSO.DeleteFolder LegacyPath, True
End If
If (FSO.FileExists(LegacyStartupPath)) Then
	FSO.DeleteFile LegacyStartupPath, True
End If
'Here legacy wfh uninstallation is over

If (FSO.FolderExists(Path)) Then
	FSO.DeleteFolder Path, True
End If
If NOT (FSO.FolderExists("C:\visualiq")) Then
	FSO.CreateFolder("C:\visualiq")
End If
FSO.CreateFolder(Path)

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

'write token
Set objFSO=CreateObject("Scripting.FileSystemObject")
outFile=Path & "\pinyourlocation-client\token"
Set objFile = objFSO.CreateTextFile(outFile,True)
objFile.Write Token
objFile.Close

'write start.vbs
Set objFSO=CreateObject("Scripting.FileSystemObject")
outFile=Path & "\pinyourlocation-client\start.vbs"
Set objFile = objFSO.CreateTextFile(outFile,True)
objFile.Write "CreateObject(""Wscript.Shell"").Run ""node.exe cron.js"", 0, True"
objFile.Close

'create shortcut
Set objShell = WScript.CreateObject("WScript.Shell")
Set lnk = objShell.CreateShortcut("C:\ProgramData\Microsoft\Windows\Start Menu\Programs\StartUp\start_pinyourlocation.LNK")

lnk.TargetPath = Path & "\pinyourlocation-client\start.vbs"
lnk.WorkingDirectory =  Path & "\pinyourlocation-client\"
lnk.Save
Set lnk = Nothing

Dim objShell
Set objShell = Wscript.CreateObject("WScript.Shell")
objShell.CurrentDirectory=Path & "\pinyourlocation-client\"
objShell.Run Path & "\pinyourlocation-client\start.vbs" 
Set objShell = Nothing

MsgBox("Setup Complete")