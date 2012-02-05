import sys

from bpm import conf, emap, faclient

def functionate(genes):
    c = faclient.FuncassociateClient()
    response = c.functionate(query=genes, 
                             species='Saccharomyces cerevisiae', 
                             namespace='sgd_systematic',
                             genespace=list(emap.genes))

    # Lets label the info for each GO term, shall we?
    # Names ending in 'with' should be read as, for example:
    #   'Number of genes in genespace *with* this GO term annotation'
    names = ['num_with', 'num_query', 'num_genespace_with',
             'log_odds', 'unadj_p', 'adj_p', 'accession', 'goname']
    overrep = [dict(zip(names, row)) for row in response['over']]

    # Let's make a dict keyed by GO term
    goterms = {}
    for row in overrep:
        assert row['accession'] not in goterms
        goterms[row['accession']] = {
            'name': row['goname'],
            'p': row['adj_p'],
            'num_genespace_with': row['num_genespace_with'],
            'num_with': row['num_with'],
            'num_query': row['num_query'],
            'genes': set(), # see below
        }

    # Now go back and annotate the GO terms with the genes that were
    # enriched for that GO term.
    #
    # 'column_headers' are the GO terms
    # 'row_headers' are the genes
    # 'table' is a list of lists of indexes into 'column_headers' where
    #         the index of each list corresponds to an index in 'row_headers'.
    #         Thus, we have a mapping from GO term to gene :-)
    enttable = response['entity_attribute_table']
    for geneind, goinds in enumerate(enttable['table']):
        for accession in [enttable['column_headers'][i] for i in goinds]:
            try:
                gene = enttable['row_headers'][geneind]
            except IndexError:
                print >> sys.stderr, 'A gene in the Funcassociate response' \
                                     ' could not be found.'
                sys.exit(1)

            try:
                goterms[accession]['genes'].add(gene)
            except IndexError:
                print >> sys.stderr, 'A GO term in the Funcassociate response' \
                                     ' could not be found.'
                sys.exit(1)

    # This is commented out because there is a bug in Funcassociate
    # where the enrichment counts don't match the entity-attribute
    # correspondence.
    # for accession, goterm in goterms.iteritems(): 
        # assert len(goterm['genes']) != goterm['num_with'] 
    
    return goterms

