from collections import defaultdict
import csv
import os
import sys

from bpm import conf, parallel

gis = defaultdict(float)
genes = set()
numgenes = 0

def load_genes():
    '''
    Loads all of the gene pairs and their corresponding interaction scores
    into memory. It also keeps a set of all genes for iterative purposes.

    There is some criteria for excluding genes from this process:
    1) If an essential gene list file is provided, any gene in that file
       is excluded from the set of genes used.
    2) If an interaction score is not the result of a deletion/deletion event,
       it is excluded from the set of genes used.
    3) If an interaction score is missing or zero, it is *KEPT* in the set of
       genes used to generate BPMs with an interaction score of 0.

    This gene information is then available at the 'emap' module level, since
    they are both used pervasively throughout BPM generation.

    Finally, if we add the gene pair (g1, g2) with score S to the dictionary,
    then we'll also add (g2, g1) with score S to the dictionary. This increases
    memory usage but saves cpu cycles when looking up interaction scores.
    Basically, we force the dictionary to be a reflexive matrix.
    '''
    essentials = set()
    if conf.essentials:
        for line in open(conf.essentials):
            essentials.add(line.strip())

    for row in csv.DictReader(open(conf.emap), delimiter='\t'):
        # Ignore pairs where one or both genes are in the essential gene list
        if row['ft1_systematic_name'] in essentials or \
                row['ft2_systematic_name'] in essentials:
            continue

        # Only use interaction scores from a deletion/deletion event
        # if row['ft1_allele'] != 'deletion' or row['ft2_allele'] != 'deletion': 
            # continue 

        # If there is no interaction score, force it to be 0
        try:
            ginter = float(row['int_score'])
        except ValueError:
            ginter = 0.0

        g1, g2 = row['ft1_systematic_name'], row['ft2_systematic_name']
        if ginter < 0:
            ginter = - (ginter ** 2)
        else:
            ginter = ginter ** 2
        gis[(g1, g2)] = ginter
        gis[(g2, g1)] = ginter

        genes.add(g1)
        genes.add(g2)

    parallel.inc_counter(parallel.costs['load_genes'])

def genecount():
    '''
    A simple method to fetch the total number of genes. It uses a pretty shotty
    memoization technique.

    Actually, I don't think it's even necessary. I think taking the length
    of a set is O(1) time complexity. Hmm...
    '''
    global numgenes

    if not numgenes:
        numgenes = len(genes)

    assert numgenes > 0, 'emap.load_genes must be called first'

    return numgenes

def gi(g1, g2):
    '''
    This indexing used to be a bit more complex, but the dict should contain
    both (g1, g2) and (g2, g1). It uses more memory but speeds up execution.
    '''
    return gis[(g1, g2)]

