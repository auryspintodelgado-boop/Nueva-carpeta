; Scripto Instalador Inno Setup - Sistema AURYS

#define MyAppName "Sistema AURYS"
#define MyAppVersion "1.0"
#define MyAppPublisher "AURYS"
#define MySourceDir "C:\Users\Usuario\Desktop\sistema\Nueva-carpeta"

[Setup]
AppId={{AURYS2024}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
AppPublisher={#MyAppPublisher}
DefaultDirName={pf}\SistemaAURYS
OutputBaseFilename=SetupAURYS
Compression=lzma2
SolidCompression=yes
WizardStyle=modern
PrivilegesRequired=admin

[Languages]
Name: "spanish"; MessagesFile: "compiler:Languages\Spanish.isl"

[Tasks]
Name: "desktopicon"; Description: "Crear acceso directo en escritorio"

[Files]
Source: "{#MySourceDir}\app\*"; DestDir: "{app}\app"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\public\*"; DestDir: "{app}\public"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\writable\*"; DestDir: "{app}\writable"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\includes\*"; DestDir: "{app}\includes"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\index.php"; DestDir: "{app}"
Source: "{#MySourceDir}\spark"; DestDir: "{app}"
Source: "{#MySourceDir}\composer.json"; DestDir: "{app}"
Source: "{#MySourceDir}\env"; DestDir: "{app}"
Source: "{#MySourceDir}\README.md"; DestDir: "{app}"
Source: "{#MySourceDir}\.htaccess"; DestDir: "{app}"

[Icons]
Name: "{autoprograms}\{#MyAppName}"; Filename: "{app}\index.php"
Name: "{autodesktop}\{#MyAppName}"; Filename: "{app}\index.php"; Tasks: desktopicon

[Run]
Filename: "http://localhost/sistema"; Description: "Abrir Sistema AURYS"; Flags: postinstall nowait