<?php
require_once('includes/header.php');
require_once('includes/command_writer.php');
?>


<h3>Examples</h3>

<ul>
  <li><a href="#typical">A typical example</a></li>
  <li><a href="#noprune">Generate BPMs without pruning</a></li>
  <li><a href="#fainfo">Finding available species and namespaces for GO annotation</a></li>
</ul>

<p>For all examples below, we are using E-MAP data from the Collins et al
   dataset from the <a href="http://interactome-cmp.ucsf.edu/">Krogan Lab
   Interactome Database</a>, and a list of essential Saccharomyces cerevisiae
   genes from the
   <a href="http://www-sequence.stanford.edu/group/yeast_deletion_project/Essential_ORFs.txt">Saccharomyces Genome Deletion Project</a>.
   The files should come with
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
<?=cmd('genecentric-bpms -e essentials' . gi('yeast_emap') . bpm('output'))?>
<p>And to perform GO enrichment on the BPMs in
   <span class="code"><?=bpm('output')?></span>:</p>
<?=cmd('genecentric-go -e essentials' . gi('yeast_emap') . bpm('output') .
   gobpm('enrichment'))?>
<p>GO enrichment results will now be in the
   <span class="code"><?=gobpm('enrichment')?></span> file.</p>

<h4 id="noprune">Generate BPMs without pruning</h4>
<p>This example generates BPMs with no pruning whatsover. Namely, the number
   of BPMs produced will equal the number of unique genes in the genetic
   interaction data.</p>
<?=cmd('genecentric-bpms -e essentials ' .
       '--no-prune --minimum-size 0 --maximum-size 0 ' .
       gi('yeast_emap') . bpm('notpruned'))?>
<p>The <?=code('--no-prune')?> option is used to disable Jaccard-style pruning,
   and the <?=code('--minimum-size 0')?> and <?=code('--maximum-size 0')?>
   options are used to prevent pruning of BPMs that are either too small or
   too big.</p>

<h4 id="fainfo">Finding available species and namespaces for GO annotation</h4>
<p>If you'd like to perform GO enrichment on BPMs generated with species other
   than Saccharomyces cereivisiae, the defaults built into
   <?=code('genecentric-go')?> will need to be overwritten.</p>
<p>But first, we have to ask Funcassociate which species it supports:</p>
<?=cmd('genecentric-fainfo species')?>
<p>Which should give some output like the following:</p>
<p><code>
  Agrobacterium tumefaciens<br />
  Anaplasma phagocytophilum<br />
  ...<br />
  Homo sapiens<br />
  ...<br />
  Vibrio cholerae
</code></p>
<p>Let's use <?=code('Homo sapiens')?> as our example. While we now know that
   Funcassociate supports the species <?=code('Homo sapiens')?>, we still need
   to tell Funcassociate which namespace to use (this depends on the kind of
   gene identifiers in your genetic interaction data).
   We can query Funcassociate for the
   available namespaces for <?=code('Homo sapiens')?> like so:</p>
<?=cmd('genecentric-fainfo namespaces \'Homo sapiens\'')?>
<p>Which should give some output like the following:</p>
<p><code>
  affy_hg_u133_plus_2<br />
  affy_hg_u133a<br />
  ...<br />
  entrezgene<br />
  ...<br />
  uniprot_swissprot<br />
  uniprot_swissprot_accession
</code></p>
<p>Let's say we'd like to use the <?=code('entrezgene')?> namespace. We can
   now perform GO enrichment on our BPMs like so:</p>
<?=cmd('genecentric-go --fa-species \'Homo sapiens\' ' .
       '--fa-namespace \'entrezgene\' ' .
       gi('homo-sapiens') . bpm('homo-sapiens') . gobpm('homo-sapiens'))?>

<?php require_once('includes/footer.php'); ?>

