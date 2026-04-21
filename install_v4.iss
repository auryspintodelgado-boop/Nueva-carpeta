; Scripto Instalador Inno Setup - Sistema AURYS v4

#define MyAppName "Sistema AURYS"
#define MyAppVersion "1.0"
#define MySourceDir "C:\Users\Usuario\Desktop\sistema\Nueva-carpeta"

[Setup]
AppId={{AURYS2024}
AppName={#MyAppName}
AppVersion={#MyAppVersion}
DefaultDirName={pf}\SistemaAURYS
OutputBaseFilename=SetupAURYSv4
Compression=lzma2
SolidCompression=yes
WizardStyle=modern
PrivilegesRequired=admin

[Languages]
Name: "spanish"; MessagesFile: "compiler:Languages\Spanish.isl"

[Tasks]
Name: "desktopicon"; Description: "Crear acceso directo en escritorio"

[Files]
Source: "{#MySourceDir}\app"; DestDir: "{app}\app"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\public"; DestDir: "{app}\public"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\writable"; DestDir: "{app}\writable"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\vendor"; DestDir: "{app}\vendor"; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "{#MySourceDir}\index.php"; DestDir: "{app}"
Source: "{#MySourceDir}\spark"; DestDir: "{app}"
Source: "{#MySourceDir}\composer.json"; DestDir: "{app}"
Source: "{#MySourceDir}\env"; DestDir: "{app}"
Source: "{#MySourceDir}\.htaccess"; DestDir: "{app}"

[Icons]
Name: "{autoprograms}\{#MyAppName}"; Filename: "{app}\public\index.php"
Name: "{autodesktop}\{#MyAppName}"; Filename: "{app}\public\index.php"; Tasks: desktopicon

[Run]
Filename: "http://localhost/sistema/public"; Description: "Abrir Sistema AURYS"; Flags: postinstall nowait