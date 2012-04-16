'''
'predict.py' sets up the command line arguments for the 'genecentric-predict'
program.

It can do quite a few things in parallel (like generating random bipartitions),
so this module also does some preprocessing to setup sane defaults for
parallelization.
'''
import argparse
import multiprocessing as mp

import bpm
from bpm.cmdargs import assert_read_access

try:
    __cpus = mp.cpu_count()
except NotImplementedError:
    __cpus = 1

parser = argparse.ArgumentParser(
    description='Negative weight prediction',
    formatter_class=argparse.ArgumentDefaultsHelpFormatter)
aa = parser.add_argument
aa('geneinter', type=str,
   metavar='INPUT_GENETIC_INTERACTION_FILE', help='Location of the GI file.')
aa('predict', type=str,
   metavar='OUTPUT_PREDICT_FILE',
   help='Where the prediction output will be written.')
aa('-e', '--essential-list', dest='essentials', type=str, default=None,
   metavar='ESSENTIAL_FILE',
   help='The location of an essential gene list file. (One gene per line.) '
        'Any genes in this file will be excluded from the set of genes used '
        'to generate BPMs.')
aa('-c', '--gene-ratio', dest='C', type=float, default=0.90,
   metavar='RATIO', help='Gene ratio threshold')
aa('-j', '--jaccard', dest='jaccard', type=float, default=0.66,
   metavar='JACCARD_INDEX', help='Jaccard Index threshold')
aa('-m', '--num-bipartitions', dest='M', type=int, default=250,
   metavar='NUMBER_BIPARTITIONS', help='Number of bipartitions to generate')
aa('-r', '--opposite-ratio', dest='R', type=float, default=0.65,
   metavar='OPPOSITE_RATIO', help='Opposite gene ratio threshold')
aa('-t', '--test-ratio', dest='T', type=float, default=0.05,
   metavar='TEST_RATIO', help='Percentage of edges to cut out.')
aa('--no-squaring', dest='squaring', action='store_false',
   help='If set, genetic interaction scores will not be squared. '
        'Squaring typically speeds convergence.')
aa('--minimum-size', dest='min_size', type=int, default=3,
   metavar='MIN_SIZE', 
   help='Minimum size of BPM. Smaller BPMs are pruned. '
        'Set to 0 to disable.')
aa('--maximum-size', dest='max_size', type=int, default=25,
   metavar='MAX_SIZE', 
   help='Maximum size of BPM. Bigger BPMs are pruned. '
        'Set to 0 to disable.')
aa('-p', '--processes', dest='processes', type=int, default=__cpus,
   metavar='PROCESSES',
   help='The number of processes to run concurrently. If set to '
        '1, the multiprocessing module will not be used.')
aa('--no-jaccard', dest='pruning', action='store_false',
   help='If set, no pruning will occur. Note that --minimum-size and '
        '--maximum-size will still have an effect. Set those to 0 to '
        'disable that pruning.')
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

# Do some error checking on file inputs...
assert_read_access(conf.geneinter)
if conf.essentials > 0: # essentials list is optional
    assert_read_access(conf.essentials)

# Set the global conf variable
bpm.conf = conf

