<?php
require_once('includes/header.php');
require_once('includes/command_writer.php');

$gc_bpms = '<a href="#genecentric-bpms">' . code('genecentric-bpms') . '</a>';
$gc_emap = '<a href="#genecentric-from-emap">' . code('genecentric-from-emap') . '</a>';
$gc_go = '<a href="#genecentric-go">' . code('genecentric-go') . '</a>';
$gc_fa = '<a href="#genecentric-fainfo">' . code('genecentric-fainfo') . '</a>';

?>

<h3>Documentation</h3>

<p>This page is split into several sections. Namely, there is a section for
   each command provided by Genecentric and a section for each file format
   that is used by Genecentric.</p>
<p>Also, the goal of this page is to provide <em>explanation</em>; if you're
   looking for examples, please see our <a href="examples.php">examples</a>
   page.</p>

<ul>
  <li><a href="#commands">Commands</a>
    <ul>
      <li><a href="#genecentric-bpms">genecentric-bpms</a></li>
      <li><a href="#genecentric-from-emap">genecentric-from-emap</a></li>
      <li><a href="#genecentric-go">genecentric-go</a></li>
      <li><a href="#genecentric-fainfo">genecentric-fainfo</a></li>
    </ul>
  </li>
  <li><a href="#file">File formats</a>
    <ul>
      <li><a href="#file-gi">gi (genetic interaction data)</a></li>
      <li><a href="#file-bpm">bpm (list of BPMs)</a></li>
      <li><a href="#file-gobpm">gobpm (GO enrichment data for BPMs)</a></li>
    </ul>
  </li>
</ul>

<h4 id="commands">Commands</h4>

<h4 id="genecentric-bpms">genecentric-bpms</h4>

<h4 id="genecentric-from-emap">genecentric-from-emap</h4>

<h4 id="genecentric-go">genecentric-go</h4>

<h4 id="genecentric-fainfo">genecentric-fainfo</h4>

<h4 id="file">File formats</h4>
<p>There are three file formats used by Genecentric:
   <a href="#file-gi">genetic interaction data files</a>,
   <a href="#file-bpm">BPM files</a> and
   <a href="#file-gobpm">GO enrichment data on BPMs files</a>.
   The convention is to use the '<strong>gi</strong>', '<strong>bpm</strong>' 
   and '<strong>gobpm</strong>' file extensions for each of the formats, 
   respectively. Genecentric does not enforce them.</p>
<p>Also note that Genecentric <em>does not care</em> which gene identifiers
   are used. In fact, they are entirely arbitrary from the perspective of
   Genecentric, so long as each identifier uniquely identifies a gene.
   Therefore, the gene identifiers used in your genetic interaction data will
   be the gene identifiers used in BPM and GO BPM files. (Note: If you are
   doing GO enrichment with Genecentric, FuncAssociate is used to perform
   the anaylsis. FuncAssociate <em>does</em> care about gene identifiers,
   and you'll have to set the <a href="examples.php#fainfo">namespace</a>
   appropriately.)</p>
<p>What follows is a brief description of each file format. A more technical
   description of each format can be found in the
   <a href="https://github.com/BurntSushi/genecentric/blob/master/README"><?=code('README')?></a>
   file in the root directory of the distribution.</p>

<h4 id="file-gi">gi (genetic interaction data)</h4>
<p>Genetic interaction data can come in many different kinds of formats, and
   so it was necessary to adopt a universal and simple format as input to
   Genecentric.</p>
<p>A genetic interaction file is tab-delimited and made up of three columns:
   two gene identifiers and a genetic interaction score. There should be a line
   for every pair of genes with an interaction score.</p>
<p>If an interaction score is missing, it is assumed to be 0.0. Similarly if
   a particular gene pair is missing.</p>
<p>There should be no column headers in genetic interaction data files.</p>
<p>Please see the <?=$gc_emap?> command for more information how to transform 
   data into a <strong>gi</strong> file.</p>
<p>Sample data:</p>
<p><code>
  R0020C	YAL011W	0.152836<br>
  R0020C	YAL013W	0.172871<br>
  R0020C	YAL015C	-0.213015<br>
</code></p>

<h4 id="file-bpm">bpm (list of BPMs)</h4>
<p>BPM files are the output produced by the <?=$gc_bpms?> command.
   They are tab-delimited and both human and machine readable. Each line
   contains a single module identifier in the first column and that module's
   corresponding genes in each subsequent column. A BPM is made up of two
   modules (and thus two lines).</p>

<p>Sample data:</p>
<p><code>
  BPM0/Module1	YAL011W	YGR181W	YMR156C	...<br>
  BPM0/Module2	YML124C	YNR010W	YEL018W	...<br>
  BPM1/Module1	YML124C	YEL018W	YIL040W	...<br>
  BPM1/Module2	YGR181W	YML060W	YML041C	...<br>
</code></p>

<p><strong>Programmer's tip:</strong> The <?=code('bpm/bpmreader.py')?>
   module contains a
   <a href="./docs/bpm.bpmreader-module.html#read"><?=code('read')?></a>
   function that takes a BPM file name as a parameter and returns a list
   of BPMs as tuples of modules (where each module is a list of gene
   identifiers).</p>

<h4 id="file-gobpm">gobpm (GO enrichment data for BPMs)</h4>
<p>GO BPM files are the output produced by the <?=$gc_go?> command. They are
   designed to be human readable as plain text files, but are also machine
   readable.</p>
<p>Each entry in the <strong>gobpm</strong> file corresponds to enrichment
   analysis on each module of every BPM from the BPM input file.
   Each entry has three sections.</p>
<p>The first section is always the first line of the entry and always starts 
   with '&gt; ' and is followed by a BPM and module identifier string.</p>
<p>The second section is always the second line of the entry and corresponds to 
   a tab-delimited list of genes in the module.</p>
<p>The third section is the rest of the lines in the entry up to and not
   including the next line that starts with '&gt; ' or the end of the file.
   Each line in the third section corresponds to a GO annotation for the BPM
   module. Each GO annotation has the following information: the GO
   accession number, the p-value, the ratio of genes annotated with the term
   in its BPM module, the GO term, and a list of genes in the BPM module that
   have been annotated by this particular GO term. (Note: The list of genes
   may be absent if that particular output option was disabled with
   <?=$gc_go?>.)</p>

<p>Sample data:</p>
<p><code>
  &gt; BPM0/Module0<br>
  YAL011W	YGR181W	YMR156C	...<br>
  GO:0043044	0.000000	8/14	ATP-dependent chromatin remodeling	YML041C YNL107W YDR334W ...<br>
  GO:0034621	0.011000	9/14	cellular macromolecular complex ...	YML041C YNL107W YDR334W ...<br>
  ...<br>
  &gt; BPM0/Module1<br>
  YML124C	YNR010W	YEL018W	...<br>
  GO:0009058	0.038000	13/15	biosynthetic process	YNR010W YNL097C YMR263W ...<br>
  GO:0044249	0.038000	13/15	cellular biosynthetic process	YNR010W YNL097C YMR263W ...<br>
</code></p>
   
<p><strong>Programmer's tip:</strong> The <?=code('bpm/enrichment.py')?>
   module contains
   <a href="./docs/bpm.enrichment-module.html#read_bpm"><?=code('read_bpm')?></a>
   and
   <a href="./docs/bpm.enrichment-module.html#write_bpm"><?=code('write_bpm')?></a>
   functions for reading and writing entires in a <strong>gobpm</strong> 
   file.</p>

<?php require_once('includes/footer.php'); ?>

