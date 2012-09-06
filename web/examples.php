<?php
require_once('includes/header.php');
require_once('includes/command_writer.php');
?>


<h3>Examples</h3>

<ul>
  <li><a href="#typical">A typical example</a></li>
  <li><a href="#noprune">Generate BPMs without pruning</a></li>
  <li><a href="#genespace">Change the genespace used by FuncAssociate</a></li>
  <li><a href="#fainfo">Finding available species and namespaces for GO annotation</a></li>
  <li><a href="#fromemap">Convert an E-MAP file to a genetic interaction file</a></li>
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
   <a href="doc.php#genecentric-from-emap">documentation</a> for instruction on 
   how to convert existing data to data that can be read by Genecentric.</p>

<h4 id="typical">A typical example</h4>
<p>This example uses the default parameters to generate BPMs while excluding
   essential Yeast genes, and perform GO enrichment on those BPMs.</p>
<p>To generate BPMs:</p>
<?=cmd('genecentric-bpms -e essentials' . gi('yeast_emap') . bpm('output'))?>
<p>And to perform GO enrichment on the BPMs in
   <?=code(bpm('output'))?>:</p>
<?=cmd('genecentric-go -e essentials' . gi('yeast_emap') . bpm('output') .
   gobpm('enrichment'))?>
<p>GO enrichment results will now be in the
   <?=code(gobpm('enrichment'))?> file.</p>

<h4 id="noprune">Generate BPMs without pruning</h4>
<p>This example generates BPMs with no pruning whatsoever. Namely, the number
   of BPMs produced will equal the number of unique genes in the genetic
   interaction data.</p>
<?=cmd('genecentric-bpms -e essentials ' .
       '--no-jaccard --minimum-size 0 --maximum-size 0 ' .
       gi('yeast_emap') . bpm('notpruned'))?>
<p>The <?=code('--no-jaccard')?> option is used to disable Jaccard-style 
   pruning, and the <?=code('--minimum-size 0')?> and
   <?=code('--maximum-size 0')?> options are used to prevent pruning of BPMs 
   that are either too small or too big.</p>

<h4 id="genespace">Change the genespace used by FuncAssociate</h4>
<p>By default, Genecentric will tell FuncAssociate to use only the genes
   in the genetic interaction data as a genespace.
   In some instances, it may be desirable to specify that the default
   genespace be used, which consists of all genes in the species recorded
   by FuncAssociate. This can be accomplished using the
   <?=code('--fa-species-genespace')?> option:</p>
<?=cmd('genecentric-go --fa-species-genespace '
       . gi('yeast_emap') . bpm('output') . gobpm('enrichment'))?>

<h4 id="fainfo">Finding available species and namespaces for GO annotation</h4>
<p>If you'd like to perform GO enrichment on BPMs generated with species other
   than Saccharomyces cereivisiae, the defaults built into
   <?=code('genecentric-go')?> will need to be overwritten.</p>
<p>But first, we have to ask FuncAssociate which species it supports:</p>
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
   FuncAssociate supports the species <?=code('Homo sapiens')?>, we still need
   to tell FuncAssociate which namespace to use (this depends on the kind of
   gene identifiers in your genetic interaction data).
   We can query FuncAssociate for the
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

<h4 id="fromemap">Convert an E-MAP file to a genetic interaction file</h4>
<p>If you have an E-MAP data file but would like to convert it to a genetic
   interaction data file (which is the only format of input that Genecentric
   supports), you can use a program provided by the Genecentric package called
   <?=code('genecentric-from-emap')?>. It takes as input an E-MAP file and
   outputs a <?=gi('')?> file that can be read by Genecentric.</p>
<?=cmd('genecentric-from-emap chrombio.csv ' . gi('yeast_emap'))?>
<p>You can view more options using <?=code('genecentric-from-emap --help')?>.
   The options allow you to specify the format of the E-MAP file; particularly
   which columns have the gene identifier information and which column
   has the genetic interaction score.</p>
<p>There is more information about <?=code('genecentric-from-emap')?> and some
   advice on what to do if you have other kinds of data in the
   <a href="doc.php#genecentric-from-emap">documentation for 
   <?=code('genecentric-from-emap')?></a>.</p>

<?php require_once('includes/footer.php'); ?>

