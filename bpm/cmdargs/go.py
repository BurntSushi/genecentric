'''
'go.py' sets up the command line arguments for the 'genecentric-go' 
program.

It can send requests to funcassociate in parallel, so include some
preprocessing to determine sane defaults. (And don't let the user set the total
number of parallel process too high; we want to be nice to Funcassociate.)
'''
import argparse
import multiprocessing as mp

import bpm
from bpm.cmdargs import assert_read_access

try:
    __cpus = mp.cpu_count()

    # be nice to Funcassociate by default
    __default_cpus = min(6, mp.cpu_count())
except NotImplementedError:
    __cpus = 1
    __default_cpus = 1

parser = argparse.ArgumentParser(
    description='GO enrichment for BPMs',
    formatter_class=argparse.ArgumentDefaultsHelpFormatter)
aa = parser.add_argument
aa('emap', type=str,
   metavar='INPUT_EMAP_FILE', help='Location of the EMAP file.')
aa('bpm', type=str,
   metavar='INPUT_BPM_FILE', help='Location of the BPM file.')
aa('enrichment', type=str,
   metavar='OUTPUT_ENRICHMENT_FILE', help='Output file for GO enrichment.')
aa('-e', '--essential-list', dest='essentials', type=str, default=None,
   metavar='ESSENTIAL_FILE',
   help='The location of an essential gene list file. (One gene per line.) '
        'Any genes in this file will be excluded from the set of genes used '
        'to generate BPMs.')
aa('-s', '--sort-go-by', dest='sort_go_by', type=str, default='p',
   choices=['p', 'accession', 'name', 'num_genes_with'],
   metavar='GO_SORT',
   help='The field to sort GO enrichment by. "p" is the p-value of the '
        'GO term. "accession" is the GO identifier, i.e., "GO:...". "name" '
        'is the GO short description, i.e., "histone exchange". And '
        '"num_genes_with" is the number of genes in the BPM module that are '
        'enriched with a particular GO term.')
aa('-t', '--order-go', dest='order_go', type=str, default='asc',
   choices=['asc', 'desc'], metavar='GO_ORDER',
   help='The order in which to sort GO enrichment. "asc" for ascending, '
        'and "desc" for descending.')
aa('-p', '--processes', dest='processes', type=int, default=__default_cpus,
   metavar='CPUs', help='The number of processes to run in parallel. If set to '
                        '1, the multiprocessing module will not be used. '
                        'You should also be nice to Funcassociate and not set '
                        'this too high.')
aa('--hide-enriched-genes', dest='hide_enriched_genes', action='store_true',
   help='If set, the enriched genes for each GO term will not be written '
        'to the output file. This may (modestly) cut down on file size if '
        'there is a lot of enrichment.')

aa('--fa-species', dest='fa_species', type=str, 
   default='Saccharomyces cerevisiae', metavar='FA_SPECIES',
   help='The species to be used by Funcassociate. Use the '
        '\'funcassociate-info\' command to get a list of available species.')
aa('--fa-namespace', dest='fa_namespace', type=str,
   default='sgd_systematic', metavar='FA_NAMESPACE',
   help='The namespace to be used by Funcassociate. This can vary depending '
        'upon the gene identifiers used in your E-MAP/SGA file. '
        'Use the \'funcassociate-info\' command to get a list of available '
        'namespaces for a given species.')
aa('--fa-cutoff', dest='fa_cutoff', type=float, default=0.05, 
   metavar='FA_CUTOFF',
   help='The p-value cutoff for GO enrichment to be used with Funcassociate. '
        'It should be in the interval (0, 1].')
aa('--fa-genespace', dest='fa_genespace', action='store_true',
   help='If set, the set of genes from the provided EMAP file will be sent '
        'as the genespace to Funcassociate. Otherwise, the default species '
        'genespace will be used.')

aa('--no-progress', dest='progress', action='store_false',
   help='If set, the progress bar will not be shown.')
aa('-v', '--verbose', dest='verbose', action='store_true',
   help='If set, more output will be shown.')

conf = parser.parse_args()

# Protect the user from themselves.
# If the provided number of processes is larger than the detected number of
# CPUs, forcefully lower it to the number of CPUs.
if conf.processes > __cpus:
    conf.processes = __cpus

# Nice error messages if files don't exist...
assert_read_access(conf.emap)
assert_read_access(conf.bpm)
if conf.essentials: # optional file
    assert_read_access(conf.essentials)

# Set the global conf variable
bpm.conf = conf
