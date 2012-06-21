<?php
require_once('includes/header.php');
require_once('includes/command_writer.php');

// $gc_bpms = '<a href="#genecentric-bpms">' . code('genecentric-bpms') . '</a>'; 
// $gc_emap = '<a href="#genecentric-from-emap">' . code('genecentric-from-emap') . '</a>'; 
// $gc_go = '<a href="#genecentric-go">' . code('genecentric-go') . '</a>'; 
// $gc_fa = '<a href="#genecentric-fainfo">' . code('genecentric-fainfo') . '</a>'; 

$gc_bpms = '<a href="#genecentric-bpms">genecentric-bpms</a>';
$gc_emap = '<a href="#genecentric-from-emap">genecentric-from-emap</a>';
$gc_go = '<a href="#genecentric-go">genecentric-go</a>';
$gc_fa = '<a href="#genecentric-fainfo">genecentric-fainfo</a>';

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
<p>The Genecentric package is currently made up for four different commands:
   <?=$gc_bpms?>, <?=$gc_emap?>, <?=$gc_go?> and <?=$gc_fa?>. On Linux/Mac,
   these commands should automatically be in your PATH if you've installed
   Genecentric. On Windows, you will need to use each command by invoking
   the Python interpreter. For example, in a command prompt, you can invoke
   the <?=$gc_bpms?> command like so:</p>
<p><code>
  C:/Path/To/Python2.7/python.exe genecentric-bpms --help
</code></p>
<p>And similarly for the other commands.</p>
<p>For all Genecentric commands, the <?=code('--help')?> option will provide
   a list and a short description of every option supported by that 
   command.</p>

<h4 id="genecentric-bpms">genecentric-bpms</h4>
<p><code class="command">
  usage: genecentric-bpms [-h] [-e ESSENTIAL_FILE] [-c RATIO] [-j JACCARD_INDEX]<br>
                        [-m NUMBER_BIPARTITIONS] [--no-squaring]<br>
                        [--minimum-size MIN_SIZE] [--maximum-size MAX_SIZE]<br>
                        [-p PROCESSES] [--no-jaccard] [--no-progress] [-v]<br>
                        INPUT_GENETIC_INTERACTION_FILE OUTPUT_BPM_FILE
</code></p>

<p>Example usages
   <a href="examples.php#typical">(1)</a> and
   <a href="examples.php#noprune">(2)</a>.</p>

<p>The <?=$gc_bpms?> command produces BPMs from
   <a href="#file-gi">genetic interaction data</a>. In particular, the
   output is in the <a href="#file-bpm">BPM file format</a>. BPMs can then
   be used with <?=$gc_go?> to generate
   <a href="#file-gobpm">GO enrichment data</a> for each module of each 
   BPM.</p>

<p><?=$gc_bpms?> uses parallelization heavily. Without it, and with
   sufficiently large numbers of partitions, the run-time performance
   of Genecentric suffers. (On the order of several minutes, depending upon
   the quality of your CPU.)</p>

<dl>
  <dt><?=code('INPUT_GENETIC_INTERACTION_FILE')?></dt>
  <dd>A required parameter. It specifies the file containing the genetic 
      interaction data.</dd>

  <dt><?=code('OUTPUT_BPM_FILE')?></dt>
  <dd>A required parameter. It specifies the file that <?=$gc_bpms?> will
      write the BPM data to.</dd>

  <dt><?=code('-e ESSENTIAL_FILE, --essential-list ESSENTIAL_FILE')?></dt>
  <dd><strong>ESSENTIAL_FILE</strong> is the location of a file that contains
      a list of genes that will <strong>not</strong> be used in any
      computation. It is imperative that you specify the same value for
      <strong>ESSENTIAL_FILE</strong> for both the <?=$gc_bpms?> and
      <?=$gc_go?> command, particularly if you're using the
      <?=code('--fa-genespace')?> option in the <?=$gc_go?> command. The format 
      of <strong>ESSENTIAL_FILE</strong> is simple: one gene identifier per 
      line.</dd>

  <dt><?=code('-c RATIO, --gene-ratio RATIO')?></dt>
  <dd><strong>RATIO</strong> corresponds to the percentage of bipartitions
      in which any gene is on either the same or opposite side
      <strong>RATIO</strong>% of the time of some gene <strong>g</strong> that 
      generates a BPM. In particular, if a gene is on the same side 
      <strong>RATIO</strong>% of the time of some gene <strong>g</strong>, then
      that gene is in the same module as <strong>g</strong>. Conversely,
      if a gene is on the opposite side <strong>RATIO</strong>% of the time
      of some gene <strong>g</strong>, then that gene is in the opposite
      module as <strong>g</strong>. Otherwise, that gene is not included in
      the BPM generated by <strong>g</strong>. Decreasing this value introduces
      more variation and increasing this value decreases variation.
      By default, <strong>RATIO</strong> is set to <strong>0.9</strong>.</dd>

  <dt><?=code('-j JACCARD_INDEX, --jaccard JACCARD_INDEX')?></dt>
  <dd><strong>JACCARD_INDEX</strong> is the similarity threshold used when
      pruning the set of BPMs. In particular, a BPM is generated for every
      gene in the set of genes in the
      <a href="#file-gi">genetic interaction data</a> and thus produces
      redundant BPMs. The Jaccard index is used to prune these redundant BPMs
      such that no two BPMs in the final set have a Jaccard index similarity
      score greater than <strong>JACCARD_INDEX</strong>. Increasing this value
      will produce more BPMs and decreasing this value will produce fewer
      BPMs.  By default, <strong>JACCARD_INDEX</strong> is set to 
      <strong>0.66</strong>.</dd>

  <dt><?=code('-m NUMBER_BIPARTITIONS, --num-bipartitions NUMBER_BIPARTITIONS')?></dt>
  <dd>The number of random bipartitions to generate. As the number grows,
      the variability between BPM results decreases but the run-time of
      Genecentric increases. By default, <strong>NUMBER_BIPARTITIONS</strong>
      is set to <strong>250</strong>. (If you have a lot of CPUs to spare,
      you may get better results with <strong>500</strong> with reasonable
      run-time performance.)</dd>

  <dt><?=code('--no-squaring')?></dt>
  <dd>When set, the genetic interaction scores in the
      <a href="#file-gi">genetic interaction data</a> are not squared.
      Typically, with E-MAP data, squaring the interaction scores can lead to 
      quicker convergence on happy bipartitions. By default, interaction scores
      are squared.</dd>

  <dt><?=code('--minimum-size MIN_SIZE')?></dt>
  <dd><strong>MIN_SIZE</strong> is an integer indicating the smallest allowable
      module. If a module is found with fewer than <strong>MIN_SIZE</strong>
      genes, its corresponding BPM is pruned from the final result.
      By default, <strong>MIN_SIZE</strong> is set to <strong>3</strong>.</dd>

  <dt><?=code('--maximum-size MAX_SIZE')?></dt>
  <dd><strong>MAX_SIZE</strong> is an integer indicating the largest allowable
      module. If a module is found with more than <strong>MAX_SIZE</strong>
      genes, its corresponding BPM is pruned from the final result.
      By default, <strong>MAX_SIZE</strong> is set to <strong>25</strong>.</dd>

  <dt><?=code('-p PROCESSES, --processes PROCESSES')?></dt>
  <dd><strong>PROCESSES</strong> is the maximum number of concurrent processes
      to spawn. By default, this is set to the number of CPUs detected.
      This is not always the desired behavior; however, sometimes performance 
      is better when this number is slightly lower than the total number of 
      CPUs on your machine. If this is set to <strong>1</strong>, then 
      concurrent features will not be used.</dd>

  <dt><?=code('--no-jaccard')?></dt>
  <dd>When set, pruning based on the Jaccard index is not done. However,
      the <?=code('--minimum-size')?> and <?=code('--maximum-size')?> options
      will still have an effect if they are not manually set to 0.</dd>

  <dt><?=code('--no-progress')?></dt>
  <dd>When set, the progress bar is not shown.</dd>

  <dt><?=code('-v, --verbose')?></dt>
  <dd>Not used.</dd>
</dl>

<h4 id="genecentric-from-emap">genecentric-from-emap</h4>
<p><code class="command">
  usage: genecentric-from-emap [-h] [--delimiter DELIMITER] [--no-header]<br>
                             [--g1-name G1_NAME] [--g2-name G2_NAME]<br>
                             [--g1-allele G1_ALLELE] [--g2-allele G2_ALLELE]<br>
                             [--int-score INT_SCORE]<br>
                             INPUT_EMAP_FILE OUTPUT_GI_FILE
</code></p>

<p><a href="examples.php#fromemap">Example usage.</a></p>
<p>This command is dedicated soley to transforming E-MAP data that you get
   from the wild into a <a href="#file-gi">genetic interaction data file</a>
   that can be read by Genecentric. Genecentric forces one input because
   genetic interaction data can come in many different formats; it would be
   infeasible to build in support for all of them. (With that said, we may
   provide other commands in the future to convert formats if they are popular
   enough.)</p>
<p>The <?=$gc_emap?> is actually quite simple, and if you have some programming
   experience, you could very easily convert any genetic interaction into
   the <a href="#file-gi">format that Genecentric understands</a>. All 
   Genecentric needs is a tab-delimited file with three columns where
   each row represents a genetic interaction: the first two columns are the 
   gene identifiers in the genetic interaction, and the third column is the
   genetic interaction score.</p>
<p><?=$gc_emap?> only requires that your E-MAP data be in some delimited file,
   where the delimiter can be specified using the <?=code('--delimiter')?>
   option.</p>
<p>The parameters of the <?=$gc_emap?> command are set by default to work with
   the 
   <a href="http://interactome-cmp.ucsf.edu/sgiDownloads.php">Collins et al.</a>
   data set.</p>

<p>An explanation of each of the options:</p>
<dl>
  <dt><?=code('INPUT_EMAP_FILE')?></dt>
  <dd>A required parameter. It specifies the file containing the raw E-MAP 
      data.</dd>

  <dt><?=code('OUTPUT_GI_FILE')?></dt>
  <dd>A required parameter. It specifies the file that <?=$gc_emap?> will write 
      the genetic interaction data to.</dd>

  <dt><?=code('--delimiter DELIMITER')?></dt>
  <dd>The character that separates each field in your E-MAP data. By default,
      the <strong>DELIMITER</strong> is set to a tab.</dd>

  <dt><?=code('--no-header')?></dt>
  <dd>When set, <?=$gc_emap?> will start reading data from your E-MAP file
      on the first line. When this option is omitted, <?=$gc_emap?> will
      assume the first line contains column headers and will thus ignore 
      it.</dd>

  <dt><?=code('--g1-name G1_NAME')?></dt>
  <dd><strong>G1_NAME</strong> is the column number that contains the first
      gene identifier in each pair. Column numbers start from 0.</dd>

  <dt><?=code('--g2-name G2_NAME')?></dt>
  <dd><strong>G2_NAME</strong> is the column number that contains the second
      gene identifier in each pair. Column numbers start from 0.</dd>

  <dt><?=code('--g1-allele G1_ALLELE')?></dt>
  <dd><strong>G1_ALLELE</strong> is the column number that contains the type
      of genetic interaction for the first gene. This is used with the 
      <a href="http://interactome-cmp.ucsf.edu/sgiDownloads.php">Collins et al.</a>
      data set to omit all genetic interactions that aren't "deletion"
      genetic interactions. If your E-MAP data set does not have this
      information, set <strong>G1_ALLELE</strong> to -1. It will be be 
      ignored. Column numbers start from 0.</dd>

  <dt><?=code('--g2-allele G2_ALLELE')?></dt>
  <dd><strong>G2_ALLELE</strong> is the column number that contains the type
      of genetic interaction for the second gene. This is used with the 
      <a href="http://interactome-cmp.ucsf.edu/sgiDownloads.php">Collins et al.</a>
      data set to omit all genetic interactions that aren't "deletion"
      genetic interactions. If your E-MAP data set does not have this
      information, set <strong>G2_ALLELE</strong> to -1. It will be be 
      ignored. Column numbers start from 0.</dd>

  <dt><?=code('--int-score INT_SCORE')?></dt>
  <dd><strong>INT_SCORE</strong> is the column number that contains the
      genetic interaction score. It is okay if the score is missing from some
      rows; it will be automatically set to 0. Column numbers start from 
      0.</dd>
</dl>

<h4 id="genecentric-go">genecentric-go</h4>
<p><code class="command">
  usage: genecentric-go [-h] [-e ESSENTIAL_FILE] [-s GO_SORT] [-t GO_ORDER]<br>
                      [-p PROCESSES] [--hide-enriched-genes]<br>
                      [--fa-species FA_SPECIES] [--fa-namespace FA_NAMESPACE]<br>
                      [--fa-cutoff FA_CUTOFF] [--fa-genespace] [--no-progress]<br>
                      [-v]<br>
                      INPUT_GENETIC_INTERACTION_FILE INPUT_BPM_FILE<br>
                      OUTPUT_ENRICHMENT_FILE
</code></p>
<p>Example usages
   <a href="examples.php#typical">(1)</a>,
   <a href="examples.php#genespace">(2)</a> and
   <a href="examples.php#fainfo">(3)</a>.</p>
<p>The <?=$gc_go?> command performs GO enrichment analysis on a set of
   BPMs generated by the <?=$gc_bpms?> command. It takes as input both a
   <a href="#file-gi">genetic interaction data file</a> and a
   <a href="#file-bpm">BPM file</a> and produces a
   <a href="#file-gobpm">GO BPM file</a> as output.</p>
<p>Most of the options for the <?=$gc_go?> command are for configuring
   the behavior of FuncAssociate.</p>
<p>Because <?=$gc_go?> uses FuncAssociate, an Internet connection is required
   in order for <?=$gc_go?> to run.</p>
<p>Please make sure to familiarize yourself with the
   <a href="#file-gobpm">GO BPM file format</a>, as some of the options
   described below are related to the output format.</p>

<p>An explanation of each of the options:</p>
<dl>
  <dt><?=code('INPUT_GENETIC_INTERACTION_FILE')?></dt>
  <dd>A required parameter. It specifies the file containing the genetic 
      interaction data.</dd>

  <dt><?=code('INPUT_BPM_FILE')?></dt>
  <dd>A required parameter. It specifies the file containing the BPMs
      generated by <?=$gc_bpms?>.</dd>

  <dt><?=code('OUTPUT_ENRICHMENT_FILE')?></dt>
  <dd>A required parameter. It specifies the file that <?=$gc_go?> will write 
      the GO BPM data to.</dd>

  <dt><?=code('-e ESSENTIAL_FILE, --essential-list ESSENTIAL_FILE')?></dt>
  <dd><strong>ESSENTIAL_FILE</strong> is the location of a file that contains
      a list of genes that will <strong>not</strong> be used in any
      computation. It is imperative that you specify the same value for
      <strong>ESSENTIAL_FILE</strong> for both the <?=$gc_bpms?> and
      <?=$gc_go?> command, particularly if you're using the
      <?=code('--fa-genespace')?> option described below. The format of 
      <strong>ESSENTIAL_FILE</strong> is simple: one gene identifier per 
      line.</dd>

  <dt><?=code('-s GO_SORT, --sort-go-by GO_SORT')?></dt>
  <dd>This option controls the order in which GO annotations are sorted for
      each entry in the <a href="#file-gobpm">GO BPM</a> file.
      <strong>GO_SORT</strong> can be one of four values: <strong>p</strong>,
      <strong>accession</strong>, <strong>name</strong> or
      <strong>num_genes_with</strong>.
      If it's <strong>p</strong>, then the GO annotations are sorted by their
      p-values.
      If it's <strong>accession</strong>, then the GO annotations are sorted
      by their accession numbers (which look like "GO:0000001").
      If it's <strong>name</strong>, then the GO annotations are sorted
      by their GO term name, i.e., "histone exchange."
      If it's <strong>num_genes_with</strong>, then the GO annotations are
      sorted by the number of genes in the particular BPM module that are
      enriched with that GO term. The default value is <strong>p</strong>.</dd>

  <dt><?=code('-t GO_ORDER, --order-go GO_ORDER')?></dt>
  <dd>This controls the order of the sort used with the
      <?=code('--sort-go-by')?> option. Namely, <strong>GO_ORDER</strong>
      can either be <strong>asc</strong> or <strong>desc</strong> where
      the former corresponds to ascending or increasing order, and the latter
      corresponds to descending or decreasing order. The default is
      <strong>asc</strong>.</dd>

  <dt><?=code('-p PROCESSES, --processes PROCESSES')?></dt>
  <dd><strong>PROCESSES</strong> is the number of processes that should be 
      spawned to run concurrently. For the <?=$gc_go?> command, this amounts
      to the number of simultaneous requests sent to FuncAssociate.
      By default, this is set to the number of
      CPUs detected on your machine or <strong>6</strong> if there are more
      than 6 CPUs on your machine. This is to make sure that we don't launch
      too many simulanteous requests to FuncAssociate on accident. However,
      you may set as large a number as you wish manually.</dd>

  <dt><?=code('--hide-enriched-genes')?></dt>
  <dd>When this is set, the enriched genes for each GO annotation in the
      <a href="#file-gobpm">GO BPM output</a> are omitted. Depending upon
      the number of modules in your BPM set, this may decrease file size
      modestly. It may also be useful to make the file easier to read if you
      don't care about this information.</dd>

  <dt><?=code('--fa-species FA_SPECIES')?></dt>
  <dd><strong>FA_SPECIES</strong> should be set to the species that your genes
      belong to. You can get a list of species supported by FuncAssociate
      using the <?=$gc_fa?> command. By default, <strong>FA_SPECIES</strong>
      is set to <em>Saccharomyces cerevisiae</em>.</dd>

  <dt><?=code('--fa-namespace FA_NAMESPACE')?></dt>
  <dd><strong>FA_NAMESPACE</strong> should be set to the namespace that your
      gene identifiers conform to. You can get a list of namespaces supported
      by FuncAssociate using the <?=$gc_fa?> command. By default,
      <strong>FA_NAMESPACE</strong> is set to 
      <strong>sgd_systematic</strong>.</dd>

  <dt><?=code('--fa-cutoff FA_CUTOFF')?></dt>
  <dd><strong>FA_CUTOFF</strong> should be set to a p-value in the interval
      <strong>(0, 1]</strong>. Only GO annotations with a p-value less than
      or equal to this cutoff will be returned by FuncAssociate. By default,
      <strong>FA_CUTOFF</strong> is set to <strong>0.05</strong>.</dd>

  <dt><?=code('--fa-genespace')?></dt>
  <dd>When set, the genespace sent to FuncAssociate will be equivalent to
      the set of genes found in the
      <a href="#file-gi">genetic interaction data</a> file. When not set,
      the genespace used will be the FuncAssociate default: all genes
      in the GO associations file for the species used.</dd>

  <dt><?=code('--no-progress')?></dt>
  <dd>When set, the progress bar is not shown.</dd>

  <dt><?=code('-v, --verbose')?></dt>
  <dd>Not used.</dd>
</dl>

<h4 id="genecentric-fainfo">genecentric-fainfo</h4>
<p><code class="command">
  usage: genecentric-fainfo [-h] [-v] QUERY_COMMAND [QUERY_SPECIES]
</code></p>
<p><a href="examples.php#fainfo">Example usage.</a></p>
<p><?=$gc_fa?> is a simple command designed to return lists of species
   and namespaces supported by FuncAssociate. Namely, 
   <strong>QUERY_COMMAND</strong> is either <?=code('species')?> or
   <?=code('namespaces')?>. In the case of the latter, <?=$gc_fa?> takes a
   second parameter <strong>QUERY_SPECIES</strong>&mdash;which is the name
   of a species in the list returned by using <?=code('species')?>
   <strong>QUERY_COMMAND</strong>.</p>
<p>The values returned by <?=$gc_fa?> can be used with the
   <?=code('--fa-species')?> and <?=code('--fa-namespaces')?> options of
   the <?=$gc_go?> command.</p>
<p>The species list is self-explanatory; the species belonging to the genes
   you're using should be used.</p>
<p>Your choice from the namespaces list depends upon what kind of gene 
   identifiers you're using.
   For example, with <em>Saccharomyces cerevisiae</em> and the
   <a href="http://interactome-cmp.ucsf.edu/sgiDownloads.php">Collins et al.</a>
   data set, the namespace is <strong>sgd_systematic</strong> which uses
   gene identifiers that look like "YAL054C".</p>
<p>If you're not sure which namespace to use, there should be an example
   of a gene identifier next to each namespace name when you run
   <?=$gc_fa?> with the namespaces <strong>QUERY_COMMAND</strong>.</p>

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
<p>Note that an interaction score must always be present in the third column.
   If the source data omits an interaction, use 0.0 as the genetic interaction
   score in the third column. (You may also omit the gene pair entirely, in
   which case, its interaction is considered to be zero.)</p>
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

