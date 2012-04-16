'''
The predict module is responsible for trying different prediction schemes
on sets of partitions.
'''

from bpm import conf, geneinter

def negative_edges(parts):
    '''
    Traverse all 'test' edges, checks whether the endpoints are in opposing
    partitions R% of the time, and only reports those edges with a confidence
    score.
    '''
    predictions = [] # [(gene1, gene2, confidence, real weight)]
    for g1, g2 in geneinter.test:
        opprat = ratio_opposite(parts, g1, g2)
        if opprat >= conf.R:
            predictions.append((g1, g2, opprat, geneinter.test[(g1, g2)]))
    return sorted(predictions, key=lambda (g1, g2, cf, real): cf, reverse=True)

def ratio_opposite(parts, g1, g2):
    '''
    The percentage of times that g1 and g2 are in opposing partitions.
    '''
    freq = 0
    for A, B in parts:
        if (g1 in A and g2 in B) or (g1 in B and g2 in A):
            freq += 1
    return float(freq) / float(len(parts))

