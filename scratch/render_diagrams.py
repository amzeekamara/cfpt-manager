import os
import subprocess
import re

chrome_path = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
if not os.path.exists(chrome_path):
    chrome_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

print(f"Using browser: {chrome_path}")

# Read markdown
with open('docs/DIAGRAMME_CAS_UTILISATION.md', 'r', encoding='utf-8') as f:
    text = f.read()

# Extract mermaid blocks
mermaid_blocks = re.findall(r'```mermaid\n(.*?)```', text, re.DOTALL)
print(f"Found {len(mermaid_blocks)} blocks")

diagram_meta = [
    {
        "id": "01_hierarchie_acteurs",
        "title": "Hiérarchie & Spécialisation des 6 Acteurs du Système",
        "subtitle": "Modélisation UML des profils opérationnels à partir de l'acteur abstrait Utilisateur",
        "width": 1400,
        "height": 900
    },
    {
        "id": "02_cas_utilisation_visiteur_candidat",
        "title": "Cas d'Utilisation : Visiteur / Candidat Externe",
        "subtitle": "Espace Public, Consultation du Catalogue & Soumission du Dossier de Candidature JICA",
        "width": 1400,
        "height": 850
    },
    {
        "id": "03_cas_utilisation_candidat_admis",
        "title": "Cas d'Utilisation : Candidat Admis (Séminariste)",
        "subtitle": "Espace Personnel, Suivi Pédagogique, Assiduité, Supports & Enquêtes",
        "width": 1400,
        "height": 850
    },
    {
        "id": "04_cas_utilisation_formateur",
        "title": "Cas d'Utilisation : Formateur / Enseignant",
        "subtitle": "Espace Pédagogique, Émargement Journalier, Déclaration d'Heures & Ressources",
        "width": 1400,
        "height": 900
    },
    {
        "id": "05_cas_utilisation_administrateur",
        "title": "Cas d'Utilisation : Administrateur Principal",
        "subtitle": "Gouvernance Système, Gestion des Comptes, Rôles RBAC, Structure Pédagogique & Audit",
        "width": 1500,
        "height": 950
    },
    {
        "id": "06_cas_utilisation_coordinateur",
        "title": "Cas d'Utilisation : Coordinateur PFPT",
        "subtitle": "Planification Logistique, Formulaires Dynamiques, Validation & Dépenses Opérationnelles",
        "width": 1500,
        "height": 900
    },
    {
        "id": "07_cas_utilisation_agent_jica",
        "title": "Cas d'Utilisation : Agent Bailleur JICA",
        "subtitle": "Supervision Budgétaire, Audit des Lignes de Dépenses & Reporting Décisionnel A4",
        "width": 1400,
        "height": 850
    },
    {
        "id": "08_diagramme_global_integre_cas_utilisation",
        "title": "Diagramme Global Intégré des Cas d'Utilisation - PFPT Manager",
        "subtitle": "Vue d'ensemble transverse des interactions entre les 6 Acteurs et les Modules Métier",
        "width": 1800,
        "height": 1300
    }
]

out_dir = os.path.abspath('docs/images/diagrammes_cas_utilisation')
os.makedirs(out_dir, exist_ok=True)

for i, block in enumerate(mermaid_blocks):
    if i >= len(diagram_meta):
        break
    meta = diagram_meta[i]
    html_file = os.path.abspath(f"scratch/temp_diag_{i+1}.html")
    png_file = os.path.join(out_dir, f"{meta['id']}.png")
    
    html_content = f"""<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>{meta['title']}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {{
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #ffffff;
      margin: 0;
      padding: 24px;
      color: #0f172a;
    }}
    .diagram-card {{
      background: #ffffff;
      border: 2px solid #e2e8f0;
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05);
    }}
    .header {{
      border-bottom: 2px solid #f1f5f9;
      padding-bottom: 16px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }}
    .badge {{
      display: inline-block;
      padding: 4px 12px;
      background: #eff6ff;
      color: #1d4ed8;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-radius: 9999px;
      border: 1px solid #bfdbfe;
    }}
    .title {{
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
      margin-top: 6px;
      margin-bottom: 2px;
    }}
    .subtitle {{
      font-size: 13px;
      color: #64748b;
      font-weight: 500;
    }}
    .footer-stamp {{
      font-size: 11px;
      color: #94a3b8;
      font-weight: 700;
      text-align: right;
    }}
    .mermaid {{
      display: flex;
      justify-content: center;
      align-items: center;
      background: #fafcff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 24px;
    }}
  </style>
</head>
<body>
  <div class="diagram-card">
    <div class="header">
      <div>
        <span class="badge">UML 2.5 • Spécification PFPT Manager</span>
        <h1 class="title">{meta['title']}</h1>
        <p class="subtitle">{meta['subtitle']}</p>
      </div>
      <div class="footer-stamp">
        <div>CFPT S-J & JICA</div>
        <div style="color: #3b82f6;">ISI DAKAR - LICENCE 3 GL</div>
      </div>
    </div>
    <div class="mermaid">
{block.strip()}
    </div>
  </div>
  <script>
    mermaid.initialize({{
      startOnLoad: true,
      theme: 'neutral',
      themeVariables: {{
        primaryColor: '#e0e7ff',
        primaryTextColor: '#1e293b',
        primaryBorderColor: '#6366f1',
        lineColor: '#475569',
        secondaryColor: '#f1f5f9',
        tertiaryColor: '#ffffff',
        fontFamily: 'Plus Jakarta Sans'
      }}
    }});
  </script>
</body>
</html>"""
    
    with open(html_file, 'w', encoding='utf-8') as hf:
        hf.write(html_content)
        
    cmd = [
        chrome_path,
        '--headless=new',
        '--disable-gpu',
        '--virtual-time-budget=6000',
        '--force-device-scale-factor=2',
        f'--screenshot={png_file}',
        f"--window-size={meta['width']},{meta['height']}",
        f"file:///{html_file}"
    ]
    
    print(f"Rendering {meta['id']}...")
    res = subprocess.run(cmd, capture_output=True, text=True)
    if os.path.exists(png_file):
        print(f" -> OK! Generated {png_file} ({os.path.getsize(png_file)} bytes)")
    else:
        print(f" -> Error rendering {meta['id']}: {res.stderr}")

print("All diagrams processed!")
