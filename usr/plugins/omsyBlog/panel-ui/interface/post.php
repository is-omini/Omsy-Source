<section class="appUiKit-user-top-menu">
    <div class="head">
        <h1>Liste des chaine</h1>
    </div>
    <div class="content">
        <ul>
            <li><a href="/panel/getter?plugin=MinBlog&page=posts" data-update="embed">Liste des chaine</a></li>
            <li><a class="active" href="/panel/getter?plugin=MinBlog&page=post" data-update="embed">Ajouter une chaine</a></li>
        </ul>
    </div>
</section>
<form method="post" enctype="multipart/form-data">
<input type="txt" name="submit_form" value="true">
<section class="appUiKit-form-grid">
	<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop">
		<div class="appKitUi-input appUiKit-marginTop">
			<label>Nom</label>
			<input type="text" name="name" value="<?= CMS->GFunction->htmlDecode($getBlog['blog_title'] ?? '') ?>">
		</div>
		<div class="appUiKit-editor-html appUiKit-marginTop">
			<div>
				<button id="add-image-btn">Ajouter une image</button>
				<button id="bold-btn"><b>Gras</b></button>
				<button id="italic-btn"><i>Italique</i></button>
				<button id="underline-btn"><u>Souligné</u></button>
			</div>
			<div class="editor-appkit" contenteditable style="height: 720px; width: 100%; border: solid 1px #ccc; padding: 8px; overflow: auto;" id="editor-appkit-editcontent"><?= nl2br(CMS->GFunction->htmlDecode($getBlog['blog_content'] ?? '')) ?></div>
		</div>
		<div class="appKitUi-input appUiKit-marginTop">
			<label>Content</label>
			<textarea id="editor-appkit-rendercontent" name="content"><?= nl2br(CMS->GFunction->htmlDecode($getBlog['blog_content'] ?? '')) ?></textarea>
		</div>
	</section>
	<section class="scroll-section appUiKit-form-contenaire-color appUiKit-marginTop">
		<div class="appKitUi-input appUiKit-marginTop">
			<label>Min</label>
			<input type="text" name="min" value="<?= CMS->GFunction->htmlDecode($getBlog['blog_min']) ?? '' ?>">
		</div>
		<div class="appKitUi-input appUiKit-marginTop">
			<label>Group</label>
			<input type="text" name="group" value="<?= CMS->GFunction->htmlDecode($getBlog['blog_group']) ?? '' ?>">
		</div>
		<div class="appKitUi-input appUiKit-marginTop">
			<label>Tags</label>
			<input type="text" name="tags" value="<?= CMS->GFunction->htmlDecode($getBlog['blog_tags']) ?? '' ?>">
		</div>
	</section>
</section>
</form>
<script type="text/javascript">
const edit = document.getElementById('editor-appkit-editcontent');
const content = document.getElementById('editor-appkit-rendercontent');
const addImageBtn = document.getElementById('add-image-btn');
const boldBtn = document.getElementById('bold-btn');
const italicBtn = document.getElementById('italic-btn');
const underlineBtn = document.getElementById('underline-btn');

edit.addEventListener('input', () => {
  content.innerHTML = remplacerDivs(edit.innerHTML);
});

addImageBtn.addEventListener('click', (e) => {
  const url = prompt('Entrez l’URL de l’image :');
  if (url) {
    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Image';
    insererNodeAuCurseur(img);
    content.innerHTML = remplacerDivs(edit.innerHTML);
  }

  e.preventDefault()
});

// Bouton Gras
boldBtn.addEventListener('click', (e) => {
  document.execCommand('bold');
  edit.focus();

  e.preventDefault()
});

// Bouton Italique
italicBtn.addEventListener('click', (e) => {
  document.execCommand('italic');
  edit.focus();

  e.preventDefault()
});

// Bouton Souligné
underlineBtn.addEventListener('click', (e) => {
  document.execCommand('underline');
  edit.focus();

  e.preventDefault()
});

function remplacerDivs(html) {
  const container = document.createElement('div');
  container.innerHTML = html;

  const divs = container.querySelectorAll('div');

  divs.forEach(div => {
    if (div.innerHTML.trim() === '' || div.innerHTML.trim() === '<br>') {
      div.remove();
    } else {
      const p = document.createElement('p');
      p.innerHTML = div.innerHTML;
      for (let attr of div.attributes) {
        p.setAttribute(attr.name, attr.value);
      }
      div.replaceWith(p);
    }
  });

  return container.innerHTML;
}

// Insérer un noeud au curseur
function insererNodeAuCurseur(node) {
  const selection = window.getSelection();
  if (!selection || selection.rangeCount === 0) return;

  const range = selection.getRangeAt(0);
  range.deleteContents();
  range.insertNode(node);

  // Déplacer le curseur après le noeud inséré
  range.setStartAfter(node);
  range.collapse(true);
  selection.removeAllRanges();
  selection.addRange(range);
}
</script>