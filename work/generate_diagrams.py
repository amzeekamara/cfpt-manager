from pathlib import Path
import re
from PIL import Image, ImageDraw, ImageFont
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.section import WD_ORIENT
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

ROOT = Path(r"D:\pfpt-cfpt")
OUT = ROOT / "outputs"
OUT.mkdir(exist_ok=True)

FONT = r"C:\Windows\Fonts\arial.ttf"
FONT_B = r"C:\Windows\Fonts\arialbd.ttf"
FONT_I = r"C:\Windows\Fonts\ariali.ttf"

def font(size, bold=False, italic=False):
    return ImageFont.truetype(FONT_B if bold else (FONT_I if italic else FONT), size)

def txt(draw, xy, text, size=22, fill="#172033", bold=False, anchor=None):
    draw.text(xy, text, font=font(size, bold), fill=fill, anchor=anchor)

def wrap(text, width, size):
    # Approximate character width for Arial at the target size; sufficient for diagram labels.
    limit = max(10, int(width / (size * .53)))
    words = text.replace("\n", " ").split()
    lines, line = [], ""
    for word in words:
        trial = (line + " " + word).strip()
        if len(trial) > limit and line:
            lines.append(line); line = word
        else:
            line = trial
    if line: lines.append(line)
    return lines

def centered_lines(draw, x, y, w, h, text, size=20, color="#172033", bold=False):
    lines = wrap(text, w - 26, size)
    lh = int(size * 1.22)
    top = y + max(10, (h - lh * len(lines)) // 2)
    for i, line in enumerate(lines):
        txt(draw, (x + w//2, top + i*lh), line, size, color, bold, "ma")

def round_box(draw, box, fill, outline="#34526f", radius=16, width=3):
    draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=width)

def actor(draw, x, y, label, sub=None):
    c = "#243b53"; r=22
    draw.ellipse((x-r,y-r,x+r,y+r), outline=c, width=5)
    draw.line((x,y+r,x,y+95), fill=c, width=5)
    draw.line((x-48,y+46,x+48,y+46), fill=c, width=5)
    draw.line((x,y+95,x-43,y+145), fill=c, width=5)
    draw.line((x,y+95,x+43,y+145), fill=c, width=5)
    txt(draw,(x,y+168),label,24,"#0b2035",True,"ma")
    if sub: txt(draw,(x,y+199),sub,17,"#52667a",False,"ma")

def arrow(draw, a, b, fill="#627d98", width=3, dashed=False):
    x1,y1=a; x2,y2=b
    if dashed:
        steps=16
        for i in range(steps):
            if i%2==0:
                p1=(x1+(x2-x1)*i/steps,y1+(y2-y1)*i/steps)
                p2=(x1+(x2-x1)*(i+1)/steps,y1+(y2-y1)*(i+1)/steps)
                draw.line((p1,p2),fill=fill,width=width)
    else: draw.line((a,b),fill=fill,width=width)
    ang=__import__('math').atan2(y2-y1,x2-x1)
    ah=16
    pts=[(x2,y2),(x2-ah*__import__('math').cos(ang-.5),y2-ah*__import__('math').sin(ang-.5)),(x2-ah*__import__('math').cos(ang+.5),y2-ah*__import__('math').sin(ang+.5))]
    draw.polygon(pts,fill=fill)

def parse_dictionary():
    s=(ROOT/'DICTIONNAIRE_DE_DONNEES.md').read_text(encoding='utf-8')
    pat=re.compile(r"### <a id=\"table-([^\"]+)\"></a> .*?\(`([^`]+)`\).*?\n\n\| Élément .*?\n\|[-| :]+\n(.*?)(?=\n---|\n## |\Z)",re.S)
    tables=[]
    for m in pat.finditer(s):
        anchor,name,block=m.groups(); fields=[]
        for row in block.splitlines():
            if not row.startswith('|'): continue
            cells=[c.strip() for c in row.strip('|').split('|')]
            if len(cells)<6: continue
            fr=re.sub(r"\*", "", cells[0]).strip()
            tech=cells[1].strip('` '); typ=cells[2].strip('` '); nullable=cells[3]
            key=cells[5]
            if tech:
                fields.append({'fr':fr,'name':tech,'type':typ,'nullable':nullable,'key':key})
        tables.append({'name':name,'anchor':anchor,'fields':fields})
    if len(tables)!=28: raise RuntimeError(f'Expected 28 tables, found {len(tables)}')
    return tables

def relation_name(child, field):
    names={
        'role_id':'possède', 'cohort_id':'est rattaché à', 'seminar_id':'concerne',
        'topic_id':'porte sur', 'user_id':'appartient à', 'registration_id':'complète',
        'trainer_id':'est assuré par', 'department_id':'associe', 'expense_category_id':'est classée dans',
        'form_template_id':'répond à', 'message_id':'concerne', 'sender_id':'est envoyé par',
        'created_by':'est créé par', 'updated_by':'est modifié par', 'resource_id':'référence',
    }
    return names.get(field, 'est lié à')

def diagram_use_cases(path):
    W,H=4300,3000
    im=Image.new('RGB',(W,H),'white'); d=ImageDraw.Draw(im)
    d.rectangle((0,0,W,H),fill='#f6f8fb')
    txt(d,(W//2,70),'Diagramme de cas d’utilisation - PFPT Manager',44,'#102a43',True,'ma')
    txt(d,(W//2,125),'CFPT Sénégal-Japon / JICA',23,'#486581',False,'ma')
    system=(580,250,3720,2750); round_box(d,system,'#ffffff','#1f4e78',28,5)
    txt(d,(2150,285),'SYSTÈME PFPT MANAGER',31,'#1f4e78',True,'ma')
    actors=[(210,530,'Visiteur / Candidat','Externe'),(210,1370,'Candidat admis','Stagiaire'),(210,2210,'Formateur','Expert enseignant'),(4080,520,'Administrateur','Gestion système'),(4080,1380,'Coordinateur','Pilotage opérationnel'),(4080,2220,'Agent JICA','Consultatif')]
    for a in actors: actor(d,*a)
    modules=[
        ('Authentification & sécurité',['S’authentifier','Se déconnecter','Réinitialiser mot de passe'],650,390,820,390),
        ('Candidatures & admissions',['Déposer candidature','Téléverser pièces','Instruire et valider','Filtrer les candidats'],1530,390,820,520),
        ('Administration & structure',['Gérer utilisateurs et rôles','Gérer départements, séminaires et thèmes','Gérer éditions / cohortes','Consulter journal d’audit'],2410,390,820,520),
        ('Pédagogie & ressources',['Émarger les présences','Consulter planning / assiduité','Gérer ressources pédagogiques','Télécharger supports','Exporter stagiaires'],650,1300,820,620),
        ('Finances, budget & logistique',['Gérer événements et ressources','Suivre budget et indicateurs','Enregistrer dépenses / factures','Exporter bilan financier JICA'],1530,1300,820,520),
        ('Formulaires & communication',['Concevoir formulaires','Répondre aux enquêtes','Diffuser messages ciblés','Recevoir notifications'],2410,1300,820,520),
    ]
    centers={}
    for title,items,x,y,w,h in modules:
        round_box(d,(x,y,x+w,y+h),'#f7fbff','#8db3d3',18,3)
        d.rectangle((x,y,x+w,y+54),fill='#dbeaf7')
        txt(d,(x+w//2,y+27),title,20,'#163b5c',True,'mm')
        gap=14; cellh=(h-74-gap*(len(items)-1))//len(items)
        for i,it in enumerate(items):
            yy=y+68+i*(cellh+gap); box=(x+22,yy,x+w-22,yy+cellh)
            round_box(d,box,'#fffdf5','#668aad',cellh//2,2); centered_lines(d,box[0],box[1],box[2]-box[0],box[3]-box[1],it,18)
            centers[it]=((box[0]+box[2])//2,(box[1]+box[3])//2)
    # Direct associations, simplified by actor domain so the layout remains legible.
    associations=[('Visiteur / Candidat','Déposer candidature'),('Candidat admis','Consulter planning / assiduité'),('Candidat admis','Télécharger supports'),('Candidat admis','Répondre aux enquêtes'),('Formateur','Émarger les présences'),('Formateur','Gérer ressources pédagogiques'),('Formateur','Exporter stagiaires'),('Administrateur','Gérer utilisateurs et rôles'),('Administrateur','Gérer départements, séminaires et thèmes'),('Administrateur','Instruire et valider'),('Administrateur','Gérer éditions / cohortes'),('Administrateur','Consulter journal d’audit'),('Coordinateur','Gérer événements et ressources'),('Coordinateur','Suivre budget et indicateurs'),('Coordinateur','Enregistrer dépenses / factures'),('Coordinateur','Diffuser messages ciblés'),('Coordinateur','Concevoir formulaires'),('Agent JICA','Suivre budget et indicateurs'),('Agent JICA','Enregistrer dépenses / factures'),('Agent JICA','Exporter bilan financier JICA')]
    apos={a[2]:(a[0],a[1]+45) for a in actors}
    for an,uc in associations:
        x,y=apos[an]; tx,ty=centers[uc]
        sx=x+70 if x<W//2 else x-70
        arrow(d,(sx,y),(tx,ty),'#7b93aa',2)
    # UML include/extend rules.
    rules=[('Déposer candidature','Téléverser pièces','<<include>>'),('Instruire et valider','S’authentifier','<<include>>'),('Émarger les présences','S’authentifier','<<include>>'),('Suivre budget et indicateurs','S’authentifier','<<include>>'),('Enregistrer dépenses / factures','S’authentifier','<<include>>'),('Exporter bilan financier JICA','Suivre budget et indicateurs','<<extend>>'),('Recevoir notifications','Instruire et valider','<<extend>>')]
    for src,dst,lab in rules:
        a,b=centers[src],centers[dst]; arrow(d,a,b,'#b7791f',2,True)
        mx,my=(a[0]+b[0])//2,(a[1]+b[1])//2; txt(d,(mx,my-12),lab,14,'#8a5a10',True,'mm')
    # Legend
    round_box(d,(820,2500,3480,2690),'#fffaf0','#d6b25e',12,2)
    txt(d,(2150,2540),'Légende : trait plein = association acteur / cas ; pointillé = relation UML <<include>> ou <<extend>>.',17,'#6b4f12',False,'ma')
    txt(d,(2150,2590),'JICA intervient uniquement en lecture / consultation ; Administrateur et Coordinateur sont deux rôles distincts.',17,'#6b4f12',False,'ma')
    im.save(path)

def diagram_physical(path, tables):
    # Poster-sized view: attributes only (PK retained); foreign keys are not annotated in entity boxes.
    W,H=7200,9200; im=Image.new('RGB',(W,H),'#f4f7fa'); d=ImageDraw.Draw(im)
    txt(d,(W//2,70),'Modèle physique de données - PFPT Manager',44,'#102a43',True,'ma')
    txt(d,(W//2,128),'Attributs complets, relations nommées et cardinalités (sans annotations FK dans les tables)',22,'#486581',False,'ma')
    cols=4; left=160; top=240; colw=1660; gapx=100; gapy=70
    positions={}; boxes={}; y_by_col=[top]*cols
    # distribute modulo to create four clearly editable columns.
    for idx,t in enumerate(tables):
        col=idx%cols; x=left+col*(colw+gapx); y=y_by_col[col]
        lines=len(t['fields']); h=86+max(1,lines)*33+20
        # safety: new column-like continuation not needed at 28 tables with this canvas height.
        boxes[t['name']]=(x,y,x+colw,y+h); positions[t['name']]=(x+colw//2,y+h//2)
        y_by_col[col]+=h+gapy
    # relations behind entities
    for t in tables:
        for f in t['fields']:
            m=re.search(r'FK->([^()]+)\(id\)', f['key'])
            if not m: continue
            parent=m.group(1)
            if parent not in positions: continue
            a=positions[t['name']]; b=positions[parent]
            d.line((a,b),fill='#95a7b8',width=3)
            # cardinalities on each end and a relation name close to the middle.
            dx,dy=b[0]-a[0],b[1]-a[1]; L=max(1,(dx*dx+dy*dy)**.5)
            ux,uy=dx/L,dy/L; nx,ny=-uy,ux
            txt(d,(a[0]+ux*48+nx*18,a[1]+uy*48+ny*18),'N',17,'#4a657c',True,'mm')
            txt(d,(b[0]-ux*48+nx*18,b[1]-uy*48+ny*18),'1',17,'#4a657c',True,'mm')
            mx,my=(a[0]+b[0])//2,(a[1]+b[1])//2
            txt(d,(mx+nx*13,my+ny*13),relation_name(t['name'],f['name']),14,'#526c84',False,'mm')
    # entity boxes on top
    for t in tables:
        x1,y1,x2,y2=boxes[t['name']]
        round_box(d,(x1,y1,x2,y2),'#ffffff','#2c5d87',16,3)
        d.rounded_rectangle((x1,y1,x2,y1+52),radius=16,fill='#d8e9f6')
        d.rectangle((x1,y1+36,x2,y1+52),fill='#d8e9f6')
        txt(d,((x1+x2)//2,y1+27),t['name'],21,'#123b5b',True,'mm')
        yy=y1+64
        for f in t['fields']:
            prefix='PK  ' if 'PK' in f['key'] else '    '
            optional=' ?' if f['nullable'].strip().lower()=='oui' else ''
            line=f"{prefix}{f['name']} : {f['type']}{optional}"
            txt(d,(x1+22,yy),line,16,'#233d52', 'PK' in f['key'])
            yy+=33
    # legend at lower right
    round_box(d,(4800,8750,7030,9090),'#fffdf5','#c4a459',14,2)
    txt(d,(5915,8790),'Légende',20,'#6b4f12',True,'ma')
    txt(d,(4860,8840),'PK : clé primaire | ? : attribut facultatif',16,'#6b4f12',False)
    txt(d,(4860,8880),'Les relations portent les cardinalités 1 / N.',16,'#6b4f12',False)
    txt(d,(4860,8920),'Les clés étrangères ne sont pas affichées dans les tables.',16,'#6b4f12',False)
    im.save(path)

def set_cell_shading(cell, fill):
    tcPr=cell._tc.get_or_add_tcPr(); shd=OxmlElement('w:shd'); shd.set(qn('w:fill'),fill); tcPr.append(shd)

def set_cell_margins(cell, top=90, start=120, bottom=90, end=120):
    tc=cell._tc; tcPr=tc.get_or_add_tcPr(); tcMar=tcPr.first_child_found_in('w:tcMar')
    if tcMar is None: tcMar=OxmlElement('w:tcMar'); tcPr.append(tcMar)
    for m,v in [('top',top),('start',start),('bottom',bottom),('end',end)]:
        node=tcMar.find(qn('w:'+m))
        if node is None: node=OxmlElement('w:'+m); tcMar.append(node)
        node.set(qn('w:w'),str(v)); node.set(qn('w:type'),'dxa')

def add_code(doc, code):
    p=doc.add_paragraph(); p.paragraph_format.space_after=Pt(0)
    r=p.add_run(code); r.font.name='Consolas'; r.font.size=Pt(7.5); r.font.color.rgb=RGBColor(55,65,81)

def build_docx(path, uc_img, phys_img, tables):
    doc=Document(); sec=doc.sections[0]; sec.top_margin=Inches(.55); sec.bottom_margin=Inches(.55); sec.left_margin=Inches(.55); sec.right_margin=Inches(.55)
    normal=doc.styles['Normal']; normal.font.name='Arial'; normal.font.size=Pt(10)
    title=doc.add_paragraph(); title.alignment=WD_ALIGN_PARAGRAPH.CENTER; title.paragraph_format.space_after=Pt(4)
    r=title.add_run('DIAGRAMMES UML - PFPT MANAGER'); r.bold=True; r.font.name='Arial'; r.font.size=Pt(22); r.font.color.rgb=RGBColor(31,78,120)
    sub=doc.add_paragraph(); sub.alignment=WD_ALIGN_PARAGRAPH.CENTER; sub.paragraph_format.space_after=Pt(16); rr=sub.add_run('CFPT Sénégal-Japon / JICA - Version mise à jour'); rr.italic=True; rr.font.size=Pt(11)
    p=doc.add_paragraph(); p.add_run('Contenu : ').bold=True; p.add_run('diagramme de cas d’utilisation à six acteurs et modèle physique complet de 28 tables. Les deux diagrammes sont également fournis en PNG. Les pages suivantes fournissent une version textuelle et tabulaire modifiable dans Word.')
    doc.add_heading('1. Diagramme de cas d’utilisation', level=1)
    p=doc.add_paragraph('Les acteurs sont positionnés autour du système. L’Administrateur et le Coordinateur sont distincts ; l’Agent JICA a un rôle consultatif.')
    doc.add_picture(str(uc_img), width=Inches(7.2))
    cap=doc.add_paragraph('Figure 1 - Diagramme de cas d’utilisation du système PFPT Manager'); cap.alignment=WD_ALIGN_PARAGRAPH.CENTER; cap.runs[0].italic=True
    doc.add_page_break()
    doc.add_heading('2. Modèle physique de données', level=1)
    p=doc.add_paragraph('Les tables présentent leurs attributs, types, clé primaire et caractère facultatif. Les liaisons entre tables sont représentées par des relations nommées et leurs cardinalités ; aucune annotation de clé étrangère n’apparaît dans les tables.')
    # Physical poster is very tall; landscape A3-like section still makes it readable in Word when zoomed.
    doc.add_picture(str(phys_img), width=Inches(7.2))
    cap=doc.add_paragraph('Figure 2 - Modèle physique de données du système PFPT Manager'); cap.alignment=WD_ALIGN_PARAGRAPH.CENTER; cap.runs[0].italic=True
    doc.add_page_break()
    doc.add_heading('3. Version Word modifiable - attributs des tables', level=1)
    doc.add_paragraph('Les tableaux ci-dessous sont directement modifiables dans Word. Ils reprennent tous les champs utilisés dans le diagramme physique, sans afficher de clé étrangère.')
    for i,t in enumerate(tables,1):
        doc.add_heading(f'{i}. {t["name"]}', level=2)
        table=doc.add_table(rows=1, cols=4); table.style='Table Grid'
        hdr=table.rows[0].cells
        for cell,label in zip(hdr,['Attribut','Type','Obligatoire','Repère']):
            cell.text=label; set_cell_shading(cell,'1F4E78'); set_cell_margins(cell)
            for run in cell.paragraphs[0].runs: run.font.color.rgb=RGBColor(255,255,255); run.bold=True; run.font.size=Pt(8)
        for f in t['fields']:
            cells=table.add_row().cells
            vals=[f['name'],f['type'],'Non' if f['nullable'].strip().lower()=='oui' else 'Oui','PK' if 'PK' in f['key'] else '']
            for c,val in zip(cells,vals):
                c.text=val; set_cell_margins(c)
                for run in c.paragraphs[0].runs: run.font.size=Pt(8)
        doc.add_paragraph()
    doc.add_page_break(); doc.add_heading('4. Source PlantUML modifiable',level=1)
    doc.add_paragraph('Le code ci-dessous est volontairement fourni sous forme de texte Word modifiable. Une copie .puml est également livrée avec les PNG.')
    code=(OUT/'diagramme_cas_utilisation_pfpt_manager.puml').read_text(encoding='utf-8')
    for line in code.splitlines(): add_code(doc,line)
    doc.save(path)

def write_plantuml(path):
    code='''@startuml
left to right direction
skinparam packageStyle rectangle
skinparam shadowing true
skinparam defaultFontName Arial
actor "Visiteur / Candidat" as V
actor "Candidat admis" as C
actor "Formateur" as F
actor "Administrateur" as A
actor "Coordinateur" as CO
actor "Agent JICA\n(consultatif)" as J
rectangle "Système PFPT Manager" {
  package "Authentification & sécurité" { usecase "S'authentifier" as Auth; usecase "Se déconnecter" as Logout }
  package "Candidatures & admissions" { usecase "Déposer candidature" as Apply; usecase "Téléverser pièces" as Upload; usecase "Instruire et valider" as Validate }
  package "Administration & structure" { usecase "Gérer utilisateurs et rôles" as Users; usecase "Gérer la structure" as Struct; usecase "Gérer éditions / cohortes" as Cohorts; usecase "Journal d'audit" as Audit }
  package "Pédagogie & ressources" { usecase "Émarger les présences" as Attend; usecase "Planning / assiduité" as Plan; usecase "Ressources pédagogiques" as Res; usecase "Exporter stagiaires" as ExportT }
  package "Finances, budget & logistique" { usecase "Gérer événements / ressources" as Events; usecase "Suivre budget" as Budget; usecase "Enregistrer dépenses" as Expenses; usecase "Exporter bilan JICA" as ExportF }
  package "Formulaires & communication" { usecase "Concevoir formulaires" as Forms; usecase "Répondre aux enquêtes" as Survey; usecase "Diffuser messages" as Msg; usecase "Recevoir notifications" as Notif }
}
V --> Apply
C --> Auth; C --> Plan; C --> Res; C --> Survey; C --> Logout
F --> Auth; F --> Attend; F --> Res; F --> ExportT
A --> Auth; A --> Users; A --> Struct; A --> Cohorts; A --> Validate; A --> Audit
CO --> Auth; CO --> Events; CO --> Budget; CO --> Expenses; CO --> Msg; CO --> Forms
J --> Auth; J --> Budget; J --> Expenses; J --> ExportF
Apply ..> Upload : <<include>>
Validate ..> Auth : <<include>>
Attend ..> Auth : <<include>>
Budget ..> Auth : <<include>>
Expenses ..> Auth : <<include>>
ExportF ..> Budget : <<extend>>
Notif ..> Validate : <<extend>>
@enduml
'''
    path.write_text(code,encoding='utf-8')

def main():
    tables=parse_dictionary()
    uc=OUT/'diagramme_cas_utilisation_pfpt_manager.png'
    phy=OUT/'modele_physique_pfpt_manager.png'
    puml=OUT/'diagramme_cas_utilisation_pfpt_manager.puml'
    write_plantuml(puml)
    diagram_use_cases(uc)
    diagram_physical(phy,tables)
    build_docx(OUT/'diagrammes_pfpt_manager_mis_a_jour.docx',uc,phy,tables)
    print('created',uc,phy,OUT/'diagrammes_pfpt_manager_mis_a_jour.docx')

if __name__=='__main__': main()
