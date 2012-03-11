<?php require_once('includes/header.php'); ?>

<?php

function gi($name) {
  return "$name.<a href=\"doc.php#file-gi\">gi</a>";
}

function bpm($name) {
  return "$name.<a href=\"doc.php#file-bpm\">bpm</a>";
}

function gobpm($name) {
  return "$name.<a href=\"doc.php#file-gobpm\">gobpm</a>";
}

?>

<h3>Examples</h3>

<ul>
  <li><a href="#typical">A typical example</a></li>
</ul>

<p>For all examples below, we are using E-MAP data from the Collins et al
   dataset from the <a href="http://interactome-cmp.ucsf.edu/">Krogan Lab
   Interactome Database</a>, and a list of essential Saccharomyces cerevisiae
   genes from the
   <a href="http://www-sequence.stanford.edu/group/yeast_deletion_project/Essential_ORFs.txt">Saccharomyces Genome Deletion Project</a>. The files should come with
   the Genecentric distribution, but you can download
   <a href="files/data/yeast_emap.gi">yeast_emap.gi</a> and
   <a href="files/data/essentials">essentials</a> from us.</p>

<p>If you'd like to use other data with Genecentric, please see our
   <a href="doc.php#data">documentation</a> for instruction on how to convert
   existing data to data that can be read by Genecentric.</p>

<h4 id="typical">A typical example</h4>
<p>This example uses the default parameters to generate BPMs while excluding
   essential Yeast genes, and perform GO enrichment on those BPMs.</p>
<p>To generate BPMs:</p>
<p><code>genecentric-bpms -e essentials
   <?=gi('yeast_emap')?>
   <?=bpm('output')?></code></p>
<p>And to perform GO enrichment on the BPMs in
   <span class="code"><?=bpm('output')?></span>:</p>
<p><code>genecentric-go
   <?=gi('yeast_emap')?>
   <?=bpm('output')?>
   <?=gobpm('enrichment')?></code></p>
<p>GO enrichment results will now be in the
   <span class="code"><?=gobpm('enrichment')?></span> file.</p>

<?php require_once('includes/footer.php'); ?>

