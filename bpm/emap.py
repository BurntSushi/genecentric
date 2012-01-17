from collections import defaultdict
import csv

from bpm import conf

gis = defaultdict(float)
genes = set()
numgenes = 0

def load_genes():
    for row in csv.reader(open(conf.emap)):
        ginter = float(row[2])
        if ginter < 0:
            ginter = - (ginter ** 2)
        else:
            ginter = ginter ** 2
        gis[(row[0], row[1])] = ginter
        gis[(row[1], row[0])] = ginter

        genes.add(row[0])
        genes.add(row[1])

def genecount():
    global numgenes

    if not numgenes:
        numgenes = len(genes)

    assert numgenes > 0, 'emap.load_genes must be called first'

    return numgenes

def gi(g1, g2):
    return gis[(g1, g2)]

